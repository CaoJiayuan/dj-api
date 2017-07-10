<?php

use Dingo\Api\Routing\Router;

/** @var Dingo\Api\Routing\Router $api */
$api = app('api.router');

$api->version('v1', ['namespace' => 'App\Http\Controllers\V1'], function (Router $api) {
  $api->get('/', function () {
    return "DJ platform !";
  });
  $api->get('t', function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $exception) {

    return $exception->getTrace();
//    $a = \App\Entity\Account::find(1);

//    return $a->unfinishedTrip();

    return substr('13323148593', -4);
    return \App\Utils\YunTuUtil::around(30.57, 104.06);
  });
  $api->get('provinces', 'LocationController@provinces');
  $api->get('cities', 'LocationController@cities');
  $api->get('areas', 'LocationController@areas');
  $api->post('login', "AuthController@login");
  $api->post('register', "AuthController@register");
  $api->post('sms/code', 'SmsController@postCode');
  $api->get('truck/sizes', 'OtherController@truckSizes');
  $api->get('car/types', 'OtherController@carTypes');
  $api->get('banner','OtherController@banner');
  $api->get('about', function () {
    return view('about');
  });
  $api->get('license', function () {
    return view('license');
  });
  $api->get('rule/local', 'PayRuleController@local');
  $api->get('rule/journey', 'PayRuleController@journey');
  $api->get('rule/truck', 'PayRuleController@truck');
  $api->get('rule/chauffeur', 'PayRuleController@chauffeur');
  $api->get('rule/chauffeur/journey', 'PayRuleController@chauffeurJourney');
  $api->get('insure/seller', 'InsureController@seller');
  $api->get('insure/insurance', 'InsureController@insurance');
  $api->post('password/forgot', 'PasswordController@resetPassword');
  $api->post('hook', 'PingXXController@hook');
  $api->any('rebate', 'TraderController@rebate');

  // Role type passenger
  $api->group(['middleware' => 'api.passenger'], function (Router $api) {
    $api->get('passenger/local/detail', 'LocalController@detail');

    $api->get('passenger/local/predictPay', 'LocalController@predictPay');
    $api->get('passenger/journey/predictPay', 'JourneyController@predictPay');
    $api->get('passenger/truck/predictPay', 'TruckController@predictPay');
    $api->get('passenger/chauffeur/predictPay', 'ChauffeurController@predict');
    $api->get('passenger/chauffeur/journey/predictPay', 'ChauffeurController@predictJourney');


    // Role type passenger and authenticated
    $api->group(['middleware' => 'api.authenticate'], function (Router $api) {
      $api->get('passenger/trip/unpayed', 'OrderController@unpayed');
      $api->post('trip/comment', 'LocalController@comment');
      $api->get('passenger/trips', 'AccountController@trips');

      $api->get('passenger/truck/list', 'TruckController@getList');
      $api->get('passenger/journey/list', 'JourneyController@getList');

      $api->get('passenger/local/mine', 'LocalController@mine');
      $api->post('passenger/local/taxi', 'LocalController@taxi');
      $api->post('passenger/local/push', 'LocalController@pushDrivers');
      $api->post('passenger/local/cancel', 'LocalController@passengerCancelOrder');

      $api->post('passenger/truck/publish', 'TruckController@publish');
      $api->post('passenger/truck/cancel', 'TruckController@cancel');
      $api->get('passenger/truck/passengerAccept', 'TruckController@passengerAccept');


      $api->post('balance/pay', 'PaymentController@balanceTripPay');
      $api->post('balance/prepay', 'PaymentController@balancePrePay');

      $api->post('passenger/journey/publish', 'JourneyController@publish');
      $api->post('passenger/journey/cancel', 'JourneyController@cancel');
      $api->get('passenger/journey/passengerAccept', 'JourneyController@passengerAccept');

      $api->post('passenger/chauffeur/publish', 'ChauffeurController@publish');
      $api->post('passenger/chauffeur/journey/publish', 'ChauffeurController@journeyPublish');
      $api->post('passenger/chauffeur/cancel', 'ChauffeurController@cancel');

      $api->get('buyer/orders', 'ShopController@orders');

      $api->get('charge/trip', 'PaymentController@getTripPayData');
      $api->get('charge/wash', 'PaymentController@getWashPayData');
      $api->get('charge/insure', 'PaymentController@getInsureData');
      $api->get('charge/prepay', 'PaymentController@getPrePayData');
    });
  });

  // Role type driver
  $api->group(['middleware' => 'api.driver'], function (Router $api) {


    // Role type driver and authenticated
    $api->group(['middleware' => 'api.authenticate'], function (Router $api) {
      $api->get('driver/trip/unpayed', 'OrderController@unpayedDriver');
      $api->get('driver/trips', 'AccountController@trips');

      $api->get('driver/journey/list', 'JourneyController@getList');
      $api->get('driver/chauffeur/list', 'ChauffeurController@journeyList');
      $api->get('driver/truck/list', 'TruckController@getList');

      $api->get('driver/local/work', 'LocalController@driverWork');
      $api->post('driver/local/accept', 'LocalController@acceptTrip');
      $api->post('driver/local/cancel', 'LocalController@driverCancelOrder');
      $api->post('driver/local/in-position', 'LocalController@inPosition');
      $api->post('driver/local/finish', 'LocalController@finish');
      $api->post('driver/local/active', 'LocalController@active');
      //司机端轮询
      $api->post('driver/local/trip', 'LocalController@trip');

      $api->post('driver/truck/publish', 'TruckController@publish');
      $api->post('driver/truck/accept', 'TruckController@accept');
      $api->post('driver/truck/active', 'TruckController@active');
      $api->post('driver/truck/finish', 'TruckController@finish');
      $api->post('driver/truck/cancel', 'TruckController@cancel');

      $api->post('driver/chauffeur/accept', 'ChauffeurController@accept');
      $api->post('driver/chauffeur/cancel', 'ChauffeurController@cancel');
      $api->post('driver/chauffeur/active', 'ChauffeurController@active');
      $api->post('driver/chauffeur/finish', 'ChauffeurController@finish');
      $api->post('driver/chauffeur/in-position', 'ChauffeurController@inPosition');


      $api->post('driver/journey/publish', 'JourneyController@publish');
      $api->post('driver/journey/cancel', 'JourneyController@cancel');
      $api->post('driver/journey/accept', 'JourneyController@accept');
      $api->post('driver/journey/active', 'JourneyController@active');
      $api->post('driver/journey/finish', 'JourneyController@finish');
      $api->get('driver/journey/related', 'JourneyController@related');


      $api->get('seller/orders', 'ShopController@orders');

    });
  });
  //Just authenticated
  $api->group(['middleware' => 'api.authenticate'], function (Router $api) {
    $api->get('account/credits', 'AccountController@creditCards');
    $api->post('complain', 'OtherController@complain');
    $api->post('feedback', 'AccountController@feedback');
    $api->post('account/credits/add', 'AccountController@addCreditCard');
    $api->post('account/withdraw', 'AccountController@withdraw');
    $api->post('insure/bay', 'InsureController@bay');
    $api->post('insure/done', 'InsureController@done');

    $api->post('wash/pay', 'PaymentController@balanceWashPay');
    $api->post('insure/pay', 'PaymentController@balanceInsurePay');

    $api->post('trader/first', 'TraderController@applyFirst');
    $api->get('trip/detail', 'LocalController@detail');
    $api->get('trip/unfinished', 'AccountController@unfinishedTrip');
    $api->get('account/trips', 'AccountController@trips');
    $api->get('account/insurances', 'InsureController@orders');
    $api->get('account', 'AccountController@detail');
    $api->post('password/update', 'PasswordController@updatePassword');
    $api->post('account/edit', 'AccountController@edit');
    $api->get('account/recharge', 'PaymentController@getRechargeData');
    $api->post('account/cert/car', 'AccountController@certCar');
    $api->post('account/cert/truck', 'AccountController@certTruck');
    $api->post('account/cert/chauffeur', 'AccountController@certChauffeur');
    $api->post('account/cert/shop', 'AccountController@certShop');
    $api->post('account/cert/journey', 'AccountController@certJourney');
    $api->post('account/shop/edit', 'AccountController@editShop');
    $api->get('account/credit', 'AccountController@credit');
    $api->get('account/balance', 'AccountController@balance');

    $api->post('shop/code', 'ShopController@code');
    $api->post('journey/publish', 'JourneyController@publish');
  });
});