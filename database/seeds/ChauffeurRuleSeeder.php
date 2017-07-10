<?php

use Illuminate\Database\Seeder;

class ChauffeurRuleSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    DB::transaction(function () {
      \App\Entity\ChauffeurPayRule::create(['city_id' => 0]);
      \App\Entity\ChauffeurJourneyPayRule::create(['city_id' => 0]);
    });
  }
}
