<?php
/**
 * LocalRepository.php
 * Date: 16/5/16
 * Time: 下午5:55
 */

namespace App\Repositories;


use Api\StarterKit\Utils\ApiResponse;
use App\Entity\Account;
use App\Entity\CarModel;
use App\Entity\Comment;
use App\Entity\LocalPayRule;
use App\Entity\Order;
use App\Entity\OrderCancel;
use App\Entity\Person;
use App\Entity\Tour;
use App\Entity\Trip;
use App\Entity\TripOrder;
use App\Traits\Certificated;
use App\Traits\LocalTaxi;
use App\Traits\Payment;
use App\Traits\PushMessage;
use App\Traits\TripAndOrders;
use App\Transformers\LocalTripItem;
use App\Utils\YunTuUtil;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Model;

class LocalRepository extends Repository
{
  use PushMessage, ApiResponse, TripAndOrders, Certificated, LocalTaxi, Payment;

  /**
   * @var Trip
   */
  protected $model;

  public function taxi($tableId, $tripData)
  {
    return DB::transaction(function () use ($tableId, $tripData) {
      $trip = $this->publishTrip($tripData);

      if ($tableId) {
        YunTuUtil::$tableId = $tableId;
      }
      $yuntuData = YunTuUtil::around($tripData['latitude'], $tripData['longitude']);
      $ids = [];
      foreach ($yuntuData as $driver) {
        $ids[] = arr_get($driver, 'accountId', 0);
      }

      $pushData = get_formatted($trip, LocalTripItem::class);

      $this->doPush($ids, $trip);

      return $pushData;
    });
  }

  /**
   * @param $drivers
   * @param Trip $trip
   * @return array
   */
  public function doPush($drivers, $trip)
  {
    $accounts = [];
    $updated = [];
    $drivers = Account::whereIn('id', (array)$drivers)->get();
    foreach ($drivers as $driver) {
      $person = $this->hasCertificated(CERT_CAR, $driver);
      if ($person != null && $driver->receivable == RECEIVE_OPEN && $driver->receive_at < time()) {
        /** @var Person $person */
        if ($drivingLicense = $person->driving) {
          if ($drivingLicense->car_model >= $trip->car_model) {
            $accounts[] = $driver;
            $updated[] = $driver->id;
          }
        }
      }
    }
    Account::whereIn('id', $updated)->update(['receivable' => RECEIVE_CLOSE]);
    $result = $this->transmissionToAccounts($accounts, "你有新的行程", PUSH_LOC_PUBLISHED, ['id' => $trip->id]);

    return $result;
  }


  /**
   * Driver cancel the tour and order
   * @param $tourId
   * @param string $reason
   * @return bool|mixed
   */
  public function driverCancel($tourId, $reason = "")
  {

    list($trip, $device) = $this->cancelTrip($tourId, $reason);

    $this->transmissionToAccounts($device, "行程被司机取消", PUSH_LOC_DIR_CANCELED, ['id' => $trip->id]);

    return true;
  }


  public function passengerCancel($tourId, $reason = "")
  {

    list($trip, $device) = $this->cancelTrip($tourId, $reason, false);

    if (!$device) {
      return true;
    }

    $this->transmissionToAccounts($device, "行程被乘客取消", PUSH_LOC_PAS_CANCELED, ['id' => $trip->id]);

    return true;
  }

  /**
   * Driver accept the tour and create orders.
   * @param $tripId
   * @return mixed
   */
  public function acceptTrip($tripId)
  {
    return DB::transaction(function () use ($tripId) {
      $trip = $this->model->find($tripId);

      $account = $this->getUser();


      if ($trip->status != TRIP_PUBLISHED) {
        $account->receivable = RECEIVE_OPEN;
        $account->save();

        return false;
      }

      if ((time() - $trip->created_at > LOCAL_TRIP_TIME_LIMIT * 60)) {
        $account->receivable = RECEIVE_OPEN;
        $account->save();

        return $this->respondForbidden('行程已经超时作废');
      }
      $trip->status = TRIP_ACCEPTED;
      $trip->save();

      $account->receivable = RECEIVE_CLOSE;
      $account->working = false;

      $account->save();

      TripOrder::create([
        'driver_id' => $account->id,
        'passenger_id' => $trip->account_id,
        'trip_id' => $tripId,
        'amount' => $trip->trip_fee,
      ]);

      $push = get_formatted($trip, LocalTripItem::class);

      $this->transmissionToAccounts($trip->account, '行程被接受', PUSH_LOC_ACCEPTED,
        ['id' => $trip->id, 'account_id' => $account->id]);

      return $push;
    });
  }


  /**
   * @return Model
   */
  public function getModel()
  {
    return Trip::class;
  }


  /**
   * @param $tripId
   * @param $reason
   * @param bool $driver
   * @return array
   */
  public function cancelTrip($tripId, $reason, $driver = true)
  {
    return DB::transaction(function () use ($tripId, $reason, $driver) {
      $trip = Trip::find($tripId);
      if (!$trip) {
        return $this->respondNotFound('行程不存在');
      }
      if ($trip->status > TRIP_IN_POSITION) {
        if ($trip->status == TRIP_CANCELED) {
          return $this->respondForbidden('行程已经被取消');
        }

        return $this->respondForbidden('乘客已经上车,不能取消当前行程');
      }

      $user = $this->getUser();


      $orders = $trip->orders;

      $devices = [];

      foreach ($orders as $order) {
        if (env('IS_FINE')) {
          if ($user->orderCanceledToday(TYPE_LOCAL, ROLE_TYPE)->count() >= 2) {
            $punishment = $trip->trip_fee * CANCEL_PUNISHMENT_RATE;
            $this->changeBalance($user, -$punishment, '取消行程扣除', false);
          }
        }

        if ($driver) {
          $account = $order->passenger;
        } else {
          $account = $order->driver;
        }

        OrderCancel::create([
          'account_id' => $user->id,
          'role' => ROLE_TYPE,
          'type' => TYPE_LOCAL,
          'order_id' => $order->id,
        ]);

        $order->cancel_reason = $reason;
        $order->status = ORDER_CANCELED;
        $order->save();
        if ($account->id != $user->id) {
          $devices[] = $account;
          if (!$driver) {
            $account->receivable = RECEIVE_OPEN;
            $account->save();
          }
        }
      }

      $trip->status = TRIP_CANCELED;
      $trip->save();
      if ($driver) {
        $user->receivable = RECEIVE_CLOSE;
        $user->working = false;
        $user->save();
      }

      return [$trip, $devices];
    });
  }

  public function finish($id, $distance)
  {
    return DB::transaction(function () use ($id, $distance) {
      if (!$trip = Trip::find($id)) {
        return $this->respondNotFound('行程不存在');
      }

      if ($trip->status < TRIP_ACTIVE) {
        return $this->respondForbidden('行程尚未开始');
      }

      if ($trip->status > TRIP_CANCELED) {
        return $this->respondForbidden('行程已完成或已取消');
      }
      $trip->finish_at = Carbon::now();
      $trip->status = TRIP_FINISHED;
      $trip->save();
      $orders = $trip->orders;

      $car = $trip->car_model;
      $city = $trip->city;
      $user = $this->getUser();

      $user->receivable = RECEIVE_OPEN;
      $user->save();

      foreach ($orders as $item) {

        $cityId = 0;
        if ($city) {
          $cityId = $city->id;
        }

        /** @var LocalPayRule $r */
        $c = CarModel::findByType($car);
        $r = LocalPayRule::whereIn('city_id', [$cityId, 0])
          ->where('car_model_id', '=', $c->id)->orderBy('city_id', 'desc')->get()->first();

        $diff = $distance - $r->limit;
        if ($diff < 0) {
          $diff = 0;
        }
        $item->start_fee = $r->init_price;
        $item->distance = $distance * 1000;
        $item->distance_fee = $r->distance_price * $diff;
        $duration = ($trip->finish_at - $trip->start_at) / 60;
        $duration = round($duration);
        $item->duration = $duration;
        $item->duration_fee = $duration * $r->duration_price;
        $item->amount = $item->start_fee + $item->distance_fee + $item->duration_fee;
        $item->save();

      }
      $push = get_formatted($trip, LocalTripItem::class);

      $this->transmissionToAccounts($trip->account, '行程已结束', PUSH_LOC_FINISHED, ['id' => $trip->id]);

      return $push;
    });
  }

  public function comment($id, $score)
  {
    return DB::transaction(function () use ($id, $score) {
      $trip = Trip::find($id);
      if (!$trip) {
        return $this->respondNotFound('行程不存在');
      }

      if ($trip->status > TRIP_PAYED) {
        return get_formatted($trip, LocalTripItem::class);
      }

      $order = $trip->order;
      $user = $this->getUser();
      if ($order) {
        Comment::create([
          'score' => $score,
          'account_id' => $user->id,
          'trip_order_id' => $order->id,
          'type' => $trip->type,
        ]);
      }

      $trip->status = TRIP_COMMENTED;
      $trip->save();

      return get_formatted($trip, LocalTripItem::class);
    });
  }
}