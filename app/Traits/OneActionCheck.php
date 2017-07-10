<?php
/**
 * OneActionCheck.php
 * Date: 16/5/19
 * Time: 下午5:35
 */

namespace App\Traits;


use App\Entity\Account;

trait OneActionCheck
{

  /**
   * @param null $role
   * @return bool
   */
  public function checkAction($role = null)
  {
    /** @var Account $user */
    $user = $this->getUser();

    $trip = $user->unfinishedTrip(null, $role);
    if ($trip) {
      $type = $trip->getReadableType();

      return $this->respondForbidden("你有一条未完成的{$type}行程");
    }

    return false;
  }

  public function getUnFinished($type)
  {
    /** @var Account $user */
    $user = $this->getUser();

    $trip = $user->unfinishedTrip($type);

    return $trip;
  }
}