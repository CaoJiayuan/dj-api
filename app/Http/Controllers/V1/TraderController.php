<?php
/**
 * TruckController.php
 * Date: 16/5/17
 * Time: 上午11:01
 */

namespace App\Http\Controllers\V1;


use App\Entity\Account;
use App\Entity\Person;
use App\Entity\PlatformPay;
use App\Traits\Payment;
use Carbon\Carbon;

class TraderController extends BaseController
{
  use Payment;

  public function applyFirst()
  {
    $user = $this->getUser();

    if ($trader = $user->cert(CERT_FIRST_TRADER)) {
      if ($trader->status == CERT_FAILED) {
        $trader->delete();
        return $this->respondForbidden('你的申请已经被驳回');
      } else {
        return $this->respondForbidden('用户已经提交申请');
      }
    }

    $income = $user->incomeCredit();

    if ($income < FIRST_TRADER_APPLY_LIMIT) {
      return $this->respondForbidden('当前用户不符合成为一级代理商的条件');
    }

    Person::create([
      'account_id' => $user->id,
      'type'       => CERT_FIRST_TRADER,
      'profitable' => true,
    ]);

    return $this->respondSuccess('提交成功');
  }

  public function applySecond()
  {

  }


  public function rebate()
  {
    $token = $this->inputGet('token');

    if ($token != 'honc123456') {
      return 'Permission denied'.PHP_EOL;
    }
    \DB::transaction(function () {
      $builder = Account::rightJoin('people','account_id','=','accounts.id')->where('people.type','=',CERT_FIRST_TRADER);

      $builder->select(['accounts.id']);
      $builder->where('people.status','=',CERT_REVIEWED);
      $builder->where('people.profitable','=',true);
      $accounts = Account::whereIn('id', $builder->get())->get();

      foreach ($accounts as $account) {
        /** @var Person $trader */
        $trader = $account->cert(CERT_FIRST_TRADER);
        $id = $account->id;

        $first = Carbon::now()->addMonth(-1);

        $review = Carbon::createFromTimestamp($trader->reviewed_at);

        $reviewIn = $review->year . $review->month;

        $firstIn = $first->year . $first->month;

        $now = Carbon::now();

        if ($now->year . $now->month != $reviewIn) {

          if ($firstIn == $reviewIn) {
            $first = Carbon::createFromTimestamp(strtotime('first day of this month', strtotime('0000')));
          }

          $plat = PlatformPay::where('driver_id', $id)
            ->whereBetween('created_at', [$first, Carbon::now()])
            ->sum('amount');
          $amount = $plat / PLATFORM_RATE;

          if ($amount != 0) {
            $this->changeBalance($account, $amount * REBATE_RATE, '油费补贴');
          }
        }
      }

      return $accounts;
    });

    $info = 'Rebate at ' . date('Y-m-d H:i:s') . PHP_EOL;
    return $info;
  }
}