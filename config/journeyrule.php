<?php
/**
 * journeyrule.php
 * Date: 16/8/8
 * Time: 下午5:35
 * Created by Caojiayuan
 */

return [
  TYPE_JOURNEY => [
    'car_model_id'   => CAR_MODEL_A,
    'init_price'     => 2000,
    'distance_price' => 35,
    'limit'          => 10,
    'limit2'         => 50,
    'less_price'     => 80,
    'type'           => TYPE_JOURNEY,
  ],

  TYPE_JOURNEY_SPECIAL => [
    'children' => [

      CAR_MODEL_A => [
        'car_model_id'   => CAR_MODEL_A,
        'init_price'     => 2000,
        'distance_price' => 50,
        'limit'          => 10,
        'limit2'         => 50,
        'less_price'     => 100,
        'type'           => TYPE_JOURNEY_SPECIAL,
      ],

      CAR_MODEL_B => [
        'car_model_id'   => CAR_MODEL_B,
        'init_price'     => 3000,
        'distance_price' => 70,
        'limit'          => 10,
        'limit2'         => 50,
        'less_price'     => 100,
        'type'           => TYPE_JOURNEY_SPECIAL,
      ],

      CAR_MODEL_C => [
        'car_model_id'   => CAR_MODEL_C,
        'init_price'     => 10000,
        'distance_price' => 80,
        'limit'          => 10,
        'limit2'         => 50,
        'less_price'     => 120,
        'type'           => TYPE_JOURNEY_SPECIAL,
      ],

      CAR_MODEL_S => [
        'car_model_id'   => CAR_MODEL_S,
        'init_price'     => 10000,
        'distance_price' => 80,
        'limit'          => 10,
        'limit2'         => 50,
        'less_price'     => 120,
        'type'           => TYPE_JOURNEY_SPECIAL,
      ],
    ],
  ],

  TYPE_JOURNEY_ONLY => [

    'children' => [
      CAR_MODEL_A => [
        'car_model_id'   => CAR_MODEL_A,
        'init_price'     => 10000,
        'distance_price' => 250,
        'limit'          => 10,
        'limit2'         => 50,
        'less_price'     => 400,
        'type'           => TYPE_JOURNEY_ONLY,
      ],

      CAR_MODEL_B => [
        'car_model_id'   => CAR_MODEL_B,
        'init_price'     => 20000,
        'distance_price' => 300,
        'limit'          => 10,
        'limit2'         => 50,
        'less_price'     => 500,
        'type'           => TYPE_JOURNEY_ONLY,
      ],

      CAR_MODEL_C => [
        'car_model_id'   => CAR_MODEL_C,
        'init_price'     => 30000,
        'distance_price' => 350,
        'limit'          => 10,
        'limit2'         => 50,
        'less_price'     => 800,
        'type'           => TYPE_JOURNEY_ONLY,
      ],

      CAR_MODEL_S => [
        'car_model_id'   => CAR_MODEL_S,
        'init_price'     => 30000,
        'distance_price' => 350,
        'limit'          => 10,
        'limit2'         => 60,
        'less_price'     => 800,
        'type'           => TYPE_JOURNEY_ONLY,
      ],
    ],
  ],
];