<?php
/**
 * LocationController.php
 * Date: 16/5/16
 * Time: 下午3:50
 */

namespace App\Http\Controllers\V1;


use App\Entity\Area;
use App\Entity\City;
use App\Entity\Province;

class LocationController extends BaseController
{

  public function provinces()
  {
    return $this->respondWithCollection(Province::orderBy('id')->get(['id', 'name']));
  }

  public function cities()
  {

    $this->validate($this->request, [
      'province_id' => 'required',
    ]);
    $provinceId = $this->inputGet('province_id');

    return $this->respondWithCollection(City::whereProvinceId($provinceId)->orderBy('id')->get(['id', 'name']));
  }

  public function areas()
  {
    $this->validate($this->request, [
      'city_id' => 'required',
    ]);

    $cityId = $this->inputGet('city_id');

    return $this->respondWithCollection(Area::whereCityId($cityId)->orderBy('id')->get(['id', 'name']));
  }
}