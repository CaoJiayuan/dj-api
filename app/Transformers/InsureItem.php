<?php
/**
 * InsureItem.php
 * Date: 16/8/23
 * Time: 上午10:30
 * Created by Caojiayuan
 */

namespace App\Transformers;


use App\Entity\InsureOrder;
use League\Fractal\TransformerAbstract;

class InsureItem extends TransformerAbstract
{
  /**
   * @param InsureOrder $item
   * @return array
   */
  public function transform($item)
  {
    $insurances = $item->insurances;

//    $item->insurances = explode(';', $insurances);
    return array_get_values([
      'id',
      'car_id',
      'order_no',
      'created_at',
      'handled',
      'amount',
      'pay_amount',
      'insure.name' => 'insure_name',
      'insurances',
      'address'
    ], $item);
  }
}