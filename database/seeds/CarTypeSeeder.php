<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class CarTypeSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    Model::unguard();
    DB::transaction(function () {
      
    });
    Model::reguard();
  }
}
