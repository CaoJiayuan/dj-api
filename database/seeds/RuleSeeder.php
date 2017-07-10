<?php

use App\Entity\CarModel;
use App\Entity\LocalPayRule;
use App\Traits\ModelHelper;
use Illuminate\Database\Seeder;

class RuleSeeder extends Seeder
{
  use ModelHelper;

  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $this->seedCarModel();
    $this->seedRules();
  }

  public function seedRules()
  {
    $this->seedLocalRule();
    $this->seedJourneyRule();
  }

  public function seedLocalRule()
  {
    DB::transaction(
      function () {
        $rule = config('rulelocal');
        foreach ($rule as $key => $tripRule) {
          $car = CarModel::findByType($key);
          if ($car) {
            $tripRule['car_model_id'] = $car->id;
            $this->copy(LocalPayRule::class, $tripRule);
          }
        }
      });
  }

  public function seedCarModel()
  {
    DB::transaction(function () {
      $carModels = config('carmodels');
      foreach ($carModels as $key => $carModel) {
        CarModel::create([
          'name' => $carModel,
          'type' => $key,
        ]);
      }
    });
  }
}
