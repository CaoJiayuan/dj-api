<?php
namespace App\Http\Controllers\V1;

use App\Entity\Account;
use App\Entity\City;
use App\Traits\ApiAuthenticate;
use App\Traits\OneDeviceAccess;
use App\Transformers\AuthTransformer;
use DB;
use Illuminate\Http\Request;

class AuthController extends BaseController
{
  use ApiAuthenticate, OneDeviceAccess;

  protected $username = 'username';
  /**
   * @var AuthTransformer
   */
  protected $transformer;

  public function __construct(AuthTransformer $transformer, Request $request)
  {
    parent::__construct($request);
    $this->transformer = $transformer;

    $this->afterLogin = function (Account $account, Request $request) {
      if ($channelId = $request->get('channel_id')) {
//        dd($channelId);
        $this->checkOneDevice($account, $channelId);
        $account->channel_id = $channelId;
      }
      if ($device = $request->get('device') !== null) {
        $account->device = $device;
      }

      if ($city = $request->get('city')) {
        $c = City::findByNameOrId($city);
        if ($c) {
          $account->city_id = $c->id;
        }
      }
      $account->save();

    };

    $this->afterRegister = function (Account $account, Request $request) {
      if ($channelId = $request->get('channel_id')) {
        $this->checkOneDevice($account, $channelId);
        $account->channel_id = $channelId;
      }

      if ($nickName = $request->get('nickname')) {
        $account->nickname = $nickName;
      }

      if ($avatar = $request->get('avatar')) {
        $account->avatar = $avatar;
      }

      if ($device = $request->get('device') !== null) {
        $account->device = $device;
      }

      $account->credits = 10000;

      $account->save();
    };
  }

  public function getValidateRole()
  {
    return [
      'code'     => 'required',
      'username' => 'required|phone|max:11',
      'password' => 'required|min:6',
    ];
  }

  /**
   * @param Request $request
   * @return Account
   */
  protected function create(Request $request)
  {
    return DB::transaction(function () use ($request) {
      $data = $request->all();
      $account = Account::create([
        'username' => $data['username'],
        'password' => bcrypt($data['password']),
      ]);

      $account->sex = SEX_MALE;
      $account->avatar = null;
      $account->receivable = RECEIVE_CLOSE;
      $account->nickname = null;

      return $account;
    });
  }

  public function recheckUsername($username)
  {
    $count = Account::whereUsername($username)->count('id');

    return $count > 0;
  }
}
