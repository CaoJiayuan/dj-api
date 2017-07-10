<?php
/**
 * Payment.php
 * Date: 16/7/26
 * Time: 上午9:49
 * Created by Caojiayuan
 */

namespace App\Traits;


use App\Entity\Account;
use App\Entity\BalanceRecord;
use App\Entity\CreditRecord;

trait Payment
{
  /**
   * @param Account $account
   * @param $changes
   * @param $cause
   * @param bool $arrears
   * @return mixed
   */
  public function changeCredit($account, $changes, $cause, $arrears = true)
  {
    if (!$arrears && $changes < 0) {
      if ($account->credits < -$changes) {
        return $this->respondForbidden('用户积分不足');
      }
    }

    if ($trader = $account->cert(CERT_FIRST_TRADER)) {
      if ($changes < 0) {
        if ($account->credits + $changes < FIRST_TRADER_APPLY_LIMIT) {
          $trader->profitable = false;
          $trader->save();
        }
      } elseif ($changes > 0) {
        if ($account->credits < FIRST_TRADER_APPLY_LIMIT && $account->credits + $changes >= FIRST_TRADER_APPLY_LIMIT) {
          $trader->profitable = false;
          $trader->save();
        }
      }
    }


    return \DB::transaction(function () use ($account, $changes, $cause) {

      if ($changes != 0) {
        $account->credits += $changes;
        $account->save();
        CreditRecord::create([
          'account_id' => $account->id,
          'changes'    => $changes,
          'cause'      => $cause,
        ]);
      }
      return $account;
    });
  }


  /**
   * @param Account $account
   * @param $changes
   * @param $cause
   * @param bool $arrears
   * @return mixed
   */
  public function changeBalance($account, $changes, $cause, $arrears = true)
  {
    if (!$arrears && $changes < 0) {
      if ($account->balance < -$changes) {
        return $this->respondForbidden('用户余额不足');
      }
    }

    return \DB::transaction(function () use ($account, $changes, $cause) {

      if ($changes != 0) {
        $account->balance += $changes;
        $account->save();
        BalanceRecord::create([
          'account_id' => $account->id,
          'changes'    => $changes,
          'cause'      => $cause,
        ]);
      }
      
      return $account;
    });
  }

  /**
   * @param Account $user
   * @param $type
   * @param $amount
   * @return array
   */
  public function getTripPayData($user, $type, $amount)
  {
    switch ($type) {
      case TYPE_CHAUFFEUR:
        $credit = .04 * $amount;
      case TYPE_CHAUFFEUR_JOURNEY:
        $credit = .04 * $amount;
      case TYPE_LOCAL :
        $credit = .04 * $amount;
        break;
      case TYPE_JOURNEY:
        $credit = .04 * $amount;
        break;
      case TYPE_JOURNEY_ONLY:
        $credit = .04 * $amount;
        break;
      case TYPE_JOURNEY_SPECIAL:
        $credit = .04 * $amount;
        break;
      case TYPE_TRUCK:
        $credit = .04 * $amount;
        break;
      default :
        $credit = 0;
    }

    $credit = round($credit);

    if ($user->credits < $credit) {
      $credit = 0;
    }

    return [$amount - $credit, $credit];
  }
}