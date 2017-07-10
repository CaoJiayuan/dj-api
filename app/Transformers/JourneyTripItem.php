<?php

namespace App\Transformers;

use App\Entity\Trip;
use League\Fractal\TransformerAbstract;

class JourneyTripItem extends TransformerAbstract
{

  /**
   * @param Trip $item
   * @return array
   */
  public function transform($item)
  {
    return array_get_values([
      'id',
      'time',
      'start',
      'destination',
      'trip_fee',
      'population',
      'pool',
      'longitude',
      'latitude',
      'longitude2',
      'latitude2',
      'account.nickname',
      'account.username',
      'account.avatar',
      'account.sex',
      'dis',
      'car_id',
      'type',
      'rest_sets',
    ], $item);
  }
}