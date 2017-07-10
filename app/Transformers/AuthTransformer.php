<?php namespace App\Transformers;

use App\Entity\Account;
use App\Traits\Certificated;
use League\Fractal\TransformerAbstract;

class AuthTransformer extends TransformerAbstract
{
  use Certificated;

  /**
   * @param Account $item
   * @return array
   */
  public function transform($item)
  {
    $certCar = $item->certCar();
    $certTruck = $item->certTuck();
    $certChauffeur = $item->certChauffeur();
    $certJourney = $item->certJourney();

    if ($certTruck) {
      if ($drivingLicense = $certTruck->driving) {
        if ($truckSize = $drivingLicense->size) {
          $truckSize->type;
        }
      }
    }

    if($certCar && $dr = $certCar->driving) {
      $certCar['car_model'] = $dr->carModel();
    }
    $shop = $item->certShop();


    $data = [
      'id'         => $item->id,
      'username'   => $item->username,
      'nickname'   => $item->nickname,
      'sex'        => $item->sex,
      'avatar'     => $item->avatar,
      'channel_id' => $item->channel_id,
      'token'      => $item->token,
      'credits'    => $item->credits ?: 0,
      'balance'    => $item->balance ?: 0,
      'device'     => $item->device,
      'working'    => $item->working,
      'shop'       => array_get_values([
        'id',
        'status',
        'shop.shop_name',
        'name',
        'phone',
        'shop.s_image',
      ], $shop),
      'car'        => array_get_values([
        'id',
        'status',
        'name',
        'phone',
        'driving.car_id',
        'car_model.name' => 'car_type',
        'driver.d_image',
        'driving.dr_image',
        'driving.policy_photo',
        'driving.policy_photo_2',
        'driving.city_id',
        'status',
      ], $certCar),
      'journey'    => array_get_values([
        'id',
        'status',
        'name',
        'phone',
        'driving.car_id',
        'driver.d_image',
        'driving.dr_image',
        'driving.policy_photo',
        'driving.policy_photo_2',
        'driving.city_id',
        'driving.car_model',
        'status',
      ], $certJourney),
      'truck'      => array_get_values([
        'id',
        'status',
        'name',
        'phone',
        'driving.car_id',
        'driver.d_image',
        'driving.dr_image',
        'driving.policy_photo',
        'driving.policy_photo_2',
        'driving.city_id',
        'status',
        'driving.size.type.name' => 'truck_name',
        'driving.size',
      ], $certTruck),
      'chauffeur'  => array_get_values([
        'id',
        'name',
        'phone',
        'id_number',
        'driver.d_image',
        'driver.city_id',
        'status',
      ], $certChauffeur),
    ];

    return $data;
  }
}