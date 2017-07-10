<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{


  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
//    $this->seedDriver();
    $this->seedInsurance();
    $this->seedFunction();
  }

  public function seedDriver()
  {
    Model::unguard();

    DB::transaction(function () {
      $faker = Faker\Factory::create('zh_CN');

      /** @var \App\Repositories\AccountRepository $repo */
      $repo = app(\App\Repositories\AccountRepository::class);
      $repo->forceCopy = true;
      $account = \App\Entity\Account::create([
        'username' => '13333333333',
        'password' => bcrypt('111111'),
        'nickname' => $faker->name
      ]);

      $data = [
        'account_id' => $account->id,
        'name'       => $faker->name,
        'phone'      => '13333333333',
        'car_id'     => '川A888888',
        'brand'      => '哇哈哈',
        'dr_image'   => 'awdawdawdawaew132132131',
        'd_image'    => 'awdawdw123123kdkvsffwad',
        'car_color'  => '黑色',
        'status'     => CERT_REVIEWED,
        'type'       => CERT_CAR,
      ];

      $shop = [
        'account_id' => $account->id,
        'name'       => $faker->name,
        'phone'      => '13333333333',
        'shop_name'  => $faker->company,
        's_image'    => 'awdawdawdawaew132132131',
        'status'     => CERT_REVIEWED,
        'type'       => CERT_SHOP,
      ];
      $repo->certCar($data,'成都');
      $repo->certShop($shop);
    });
    
    
    DB::transaction(function () {
      $faker = Faker\Factory::create('zh_CN');

      /** @var \App\Repositories\AccountRepository $repo */
      $repo = app(\App\Repositories\AccountRepository::class);
      $repo->forceCopy = true;
      $account = \App\Entity\Account::create([
        'username' => '18888888888',
        'password' => bcrypt('111111'),
        'nickname' => $faker->name

      ]);

      $data = [
        'account_id' => $account->id,
        'name'       => $faker->name,
        'phone'      => '18888888888',
        'car_id'     => '川A111111',
        'brand'      => '哇哈哈',
        'dr_image'   => 'awdawdawdawaew132132131',
        'd_image'    => 'awdawdw123123kdkvsffwad',
        'car_color'  => '黑色',
        'status'     => CERT_REVIEWED,
        'type'       => CERT_CAR,
      ];

      $shop = [
        'account_id' => $account->id,
        'name'       => $faker->name,
        'phone'      => '13333333333',
        'shop_name'  => $faker->company,
        's_image'    => 'awdawdawdawaew132132131',
        'status'     => CERT_REVIEWED,
        'type'       => CERT_SHOP,
      ];
      $repo->certCar($data,'成都');
      $repo->certShop($shop);
    });

    Model::reguard();
  }

  public function seedInsurance()
  {
    Model::unguard();

    DB::transaction(function () {
      $ins = config('insurances');
      $seller = config('insures');

      foreach ($seller as $name) {
        $se = \App\Entity\Insure::create([
          'name' => $name,
        ]);
        foreach ($ins as $type => $in) {
          $inType = \App\Entity\InsuranceType::create([
            'name'      => $type,
            'insure_id' => $se->id,
          ]);
          foreach ($in as $item) {
            \App\Entity\Insurance::create([
              'insurance_type_id' => $inType->id,
              'name'              => $item,
            ]);
          }
        }
      }
    });

    Model::reguard();
  }

  public function seedFunction()
  {
    $pre = 'SET GLOBAL log_bin_trust_function_creators=1';

    DB::connection()->getPdo()->exec($pre);
    $sql = <<<SQL
CREATE FUNCTION getDistance
(
GPSLng DECIMAL(12,6),
GPSLat DECIMAL(12,6),
Lng  DECIMAL(12,6),
Lat DECIMAL(12,6)
)
RETURNS DECIMAL(12,4)
BEGIN
DECLARE result DECIMAL(12,4);
  set result=6371.004*ACOS(SIN(GPSLat/180*PI())*SIN(Lat/180*PI())+COS(GPSLat/180*PI())*COS(Lat/180*PI())*COS((GPSLng-Lng)/180*PI()));
RETURN result;
END
SQL;

    try {
      DB::connection()->getPdo()->exec($sql);
    } catch (Exception $e) {
      Log::alert("Seed function exception. {$e->getMessage()}");
    }
  }

  public function seedDummy()
  {
    Model::unguard();

    DB::transaction(function () {
    });

    Model::reguard();
  }
}
