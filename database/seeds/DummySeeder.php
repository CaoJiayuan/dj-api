<?php

use Illuminate\Database\Seeder;

class DummySeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    factory(\App\Entity\Trip::class, 30)->create();
  }
}
