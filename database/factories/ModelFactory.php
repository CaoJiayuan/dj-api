<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/


/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
  return [
    'name'           => $faker->name,
    'email'          => $faker->safeEmail,
    'password'       => bcrypt(str_random(10)),
    'remember_token' => str_random(10),
  ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Entity\Trip::class, function (Faker\Generator $faker) {
  $faker = \Faker\Factory::create('zh_CN');

  return [
    'account_id'    => rand(1, 2),
    'start'         => $faker->address,
    'city_id'       => 1,
    'destination'   => $faker->address,
    'time'          => \Carbon\Carbon::now()->addHours(rand(-8, 8)),
    'longitude'     => 104 . '.' . rand(20, 500),
    'latitude'      => 34 . '.' . rand(20, 500),
    'longitude2'    => 104 . '.' . rand(20, 500),
    'latitude2'     => 34 . '.' . rand(20, 500),
    'population'    => rand(1, 4),
    'rest_sets'     => rand(1, 4),
    'status'        => 0,
    'type'          => TYPE_JOURNEY,
    'role'          => rand(0, 1),
    'car_model'     => 0,
    'trip_fee'      => rand(20, 100) * 100,
    'truck_size_id' => rand(0, 5),
    'pool'          => rand(0, 1),
  ];
});



