<?php
/**
 * OrderController.php
 * Date: 16/5/19
 * Time: 下午3:06
 */

namespace App\Http\Controllers\V1;


use App\Entity\Order;
use App\Transformers\LocalTripItem;

class OrderController extends BaseController
{
  public function unpayed()
  {
    $user = $this->getUser();

    $trip = $user->unpayedTripPassenger();

    if (!$trip) {
      return $this->respondNotFound('当前没有未支付的行程');
    }
    return get_formatted($trip, LocalTripItem::class);
  }

  public function unpayedDriver()
  {
    $user = $this->getUser();

    return get_formatted($user->unpayedTripsDriver(), LocalTripItem::class);
  }
}