<?php
/**
 * TruckController.php
 * Date: 16/5/17
 * Time: 上午11:01
 */

namespace App\Http\Controllers\V1;


use App\Entity\ChauffeurJourneyPayRule;
use App\Entity\ChauffeurPayRule;
use App\Entity\City;
use App\Entity\JourneyPayRule;
use App\Entity\LocalPayRule;
use App\Entity\TruckPayRule;
use App\Entity\TruckType;
use Symfony\Component\HttpFoundation\Request;

class PayRuleController extends BaseController
{
  public function local()
  {
    $c = $this->getCity();

    $cityId = 0;
    if ($c) {
      $cityId = $c->id;
    }

    $builder = LocalPayRule::with('car')->whereIn('city_id', [$cityId, 0])->orderBy('car_model_id')->orderBy('city_id');

    $rule = $builder->get()->toArray();

    $i = 0;
    foreach($rule as $v)
    {
      $models[$i] = $v['car_model_id'];
      ++$i;
    }

    $data = [];
    foreach ($rule as $item) {
      $data[$item['car_model_id']] = $item;
    }
    return view('rule.local', ['data' => $data]);
  }


  public function journey(Request $request)
  {
    $c = $this->getCity();
    if (!$c) {
      $defaultRules = JourneyPayRule::defaultRules()->toArray();
    } else {
      $data = $c->journeyRule->toArray();
      $defaultRules = JourneyPayRule::defaultRules()->toArray();
      $counts = count($defaultRules);
      for($i=0;$i<$counts;$i++)
      {
        foreach($data as $v)
        {
          if($v['car_model_id']==$defaultRules[$i]['car_model_id']&&$v['type']==$defaultRules[$i]['type'])
          {
            $defaultRules[$i] = $v;
          }
        }
      }
    }
    return view('rule.journey', ['data' => $defaultRules]);
  }

  public function truck()
  {
    $this->validateRequest([
      'id' => 'required',
      'city' => 'required',
    ]);
    $city = $this->inputGet('city');
    $typeId = $this->inputGet('id');
    if (!$type = TruckType::find($typeId)) {
      return $this->respondNotFound('货车类型不存在');
    }
    $c = City::findByNameOrId($city);
    if (!$c) {
      $defaultRules = TruckPayRule::defaultRules()->whereLoose('size.truck_type_id', $typeId)->toArray();
    } else {
      $rule = $c->truckPayRule->whereLoose('size.truck_type_id', $typeId)->toArray();
      $defaultRules = TruckPayRule::defaultRules()->whereLoose('size.truck_type_id', $typeId)->toArray();
      foreach($defaultRules as $k=>$v)
      {
        foreach($rule as $r)
        {
          if($v['truck_size_id']==$r['truck_size_id'])
          {
            $defaultRules[$k] = $r;
          }
        }
      }
    }
    return view('rule.truck', ['type' => $type->toArray(), 'rule' => $defaultRules]);
  }

  public function chauffeur()
  {
    $c = $this->getCity();

    if (!$c) {
      $rule = ChauffeurPayRule::defaultRules()->toArray();
    } else {
      $rule = $c->chauffeurPayRule->toArray();
    }

    return view('rule.chauffeur', ['data' => $rule]);
  }

  public function chauffeurJourney()
  {
    $c = $this->getCity();

    if (!$c) {
      $rule = ChauffeurJourneyPayRule::defaultRules()->toArray();
    } else {
      $rule = $c->chauffeurJourneyPayRule->toArray();
    }

    return view('rule.chauffeur_jou', ['data' => $rule]);
  }

  /**
   * @return City
   */
  public function getCity()
  {
    $this->validate($this->request, [
      'city' => 'required',
    ]);
    $city = $this->inputGet('city');
    $c = City::findByNameOrId($city);

    return $c;
  }
}