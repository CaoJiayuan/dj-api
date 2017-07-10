<?php
/**
 * OrderController.php
 * Date: 16/5/19
 * Time: 下午3:06
 */

namespace App\Http\Controllers\V1;


use App\Entity\CarModel;
use App\Entity\Complain;
use App\Entity\Order;
use App\Entity\Trip;
use App\Entity\TruckType;
use App\Entity\Ads;
use App\Traits\ModelHelper;

class OtherController extends BaseController
{
  use ModelHelper;
  public function truckSizes()
  {
    return $this->respondWithCollection(TruckType::with('sizes')->get());
  }

  public function carTypes()
  {
    return $this->respondWithCollection(CarModel::get());
  }
  public function complain()
  {
    $this->validate($this->request, [
      'id'      => 'required',
      'content' => 'required',
    ]);
    $data = $this->inputAll();

    $id = $this->inputGet('id');

    $trip = Trip::find($id);

    if (!$trip) {
      return $this->respondNotFound('行程不存在');
    }

    if (!$tOrder = $trip->order) {
      return $this->respondForbidden('行程未被接受或已取消');
    }
    $driverId = $tOrder->driver_id;
    $data['driver_id'] = $driverId;
    $data['account_id'] = $this->getUser()->id;
    unset($data['id']);
    $this->copy(Complain::class, $data);

    return $this->respondSuccess('投诉内容提交成功');
  }

  public function banner()
  {
    return $this->respondWithCollection(Ads::get());
  }

}