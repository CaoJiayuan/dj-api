<?php

use App\Entity\Area;
use App\Entity\City;
use App\Entity\Province;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;


/**
 * CitiesTableSeeder.php
 * Date: 16/5/16
 * Time: 下午3:39
 */
class CitiesTableSeeder extends Seeder
{

  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $location = config('cities');

    DB::transaction(function () use ($location) {
      Model::unguard();
      foreach ($location as $item) {
        $province = $item['province_name'];
        $p = Province::create(['name' => $province]);

        if (isset($item['city'])) {
          foreach ((array)$item['city'] as $c) {
            $cityName = $c['city_name'];
            $city = City::create(['province_id' => $p['id'], 'name' => $cityName]);

            foreach (arr_get($c, 'area', []) as $area) {
              Area::create([
                'city_id' => $city->id,
                'name'    => $area,
              ]);
            }
          }
        }
      }
      Model::reguard();
    });
  }
}