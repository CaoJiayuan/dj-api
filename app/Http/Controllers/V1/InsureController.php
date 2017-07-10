<?php
/**
 * InsureController.php
 * Date: 16/7/22
 * Time: 上午10:03
 * Created by Caojiayuan
 */

namespace App\Http\Controllers\V1;


use App\Entity\InsuranceType;
use App\Entity\Insure;
use App\Entity\InsureOrder;
use App\Traits\ModelHelper;
use App\Traits\PageAble;
use App\Transformers\InsureItem;

class InsureController extends BaseController
{
  use ModelHelper, PageAble;

  public function seller()
  {
    return Insure::get(['id', 'name'])->toArray();
  }

  public function insurance()
  {

    $builder = InsuranceType::with('insurances');

    $id = $this->inputGet('insure_id', 1);

    $builder->whereInsureId($id);
    
    return $builder->get()->toArray();
  }

  public function bay()
  {
    $this->validate($this->request, [
      'insure_id'  => 'required',
      'insurances' => 'required',
      'dr_image'   => 'required',
      'car_id'     => 'required',
    ]);

    $data = $this->inputAll();

    $data['insurances'] = implode(';', (array)$data['insurances']);

    $data['account_id'] = $this->getUser()->id;

    $time = explode('.', round_fix(microtime(true), 5));
    $data['order_no'] = 'I-' . date('YmdHis') . end($time);
    $this->copy(InsureOrder::class, $data);

    return $this->respondSuccess('保险订单提交成功,请耐心等待后台处理');
  }

  public function done(InsureItem $item)
  {
    $this->validateRequest([
      'id' => 'required',
    ]);

    $id = input_get('id');

    if (!$order = InsureOrder::find($id)) {
      return $this->respondNotFound('保险订单不存在');
    }

    if ($order->handled == INSURE_DONE) {
      return $this->respondForbidden('保单已完成');
    }

    if ($order->handled != INSURE_SENT) {
      return $this->respondForbidden('保单还未发货');
    }
    
    $order->handled = INSURE_DONE;
    
    $order->save();
    
    return $this->respondWithItem($order, $item);
  }

  public function orders(InsureItem $item)
  {
    $user = $this->getUser();
    list($page, $size) = $this->getPageParam($this->request);
    $insures = InsureOrder::with('insure')->whereAccountId($user->id)->orderBy('id', 'desc')->forPage($page,
      $size)->get();

    return $this->respondWithCollection($insures, $item);
  }
}