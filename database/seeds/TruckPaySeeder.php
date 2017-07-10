<?php

use Illuminate\Database\Seeder;

class TruckPaySeeder extends Seeder
{
  use \App\Traits\ModelHelper;
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    DB::transaction(function () {
      $trucks = config('trucks');

      foreach ($trucks as $name => $truck) {
        $t = \App\Entity\TruckType::create(['name' => $name]);

        foreach ($truck as $size => $rule) {
          $s = explode('*', $size);
          $data['length'] = $s[0] * 100;
          $data['width'] = $s[1] * 100;
          $data['height'] = $s[2] * 100;
          $data['truck_type_id'] = $t->id;
          $size = $this->copy(\App\Entity\TruckSize::class, $data);
          $pay['truck_size_id'] = $size->id;
          $pay['init_price'] = $rule[0];
          $pay['distance_price'] = $rule[1];

          $this->copy(\App\Entity\TruckPayRule::class, $pay);
        }
      }
    });
  }
}
