<?php
/**
 * ApiAuthenticate.php
 * Date: 16/5/12
 * Time: 下午6:03
 */

namespace App\Traits;


use App\Entity\Account;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use JWTAuth;
use SmsManager;
use Tymon\JWTAuth\Exceptions\JWTException;
use Log;
use Tymon\JWTAuth\Facades\JWTFactory;

trait ApiAuthenticate
{

  use AuthenticatesAndRegistersUsers;

  /**
   * @var $afterRegister \Closure
   */
  protected $afterRegister = null;

  /**
   * @var $afterLogin \Closure
   */
  protected $afterLogin = null;


  protected $invalidCredentialMessage = '用户名或密码不正确';
  protected $existsUsernameMessage = '用户名已经被注册';

  public function login(Request $request)
  {

    $this->validate($request, [
      $this->loginUsername() => 'required',
      'password'             => 'required',
    ]);
    $credentials = $this->getCredentials($request);

    $data = $this->attempt($request, $credentials);
    return $data;
  }

  public function register(Request $request)
  {
    $this->validate($request, $this->getValidateRole());

    $username = $request->get($this->loginUsername());
    
    $code = $request->get('code');
    
    $this->registerValidate($username, $code);

    return $this->addUser($request);
  }

  /**
   * @param Request $request
   * @param $credentials
   * @return mixed
   */
  public function attempt(Request $request, $credentials)
  {
    $loginId = md5(uniqid() . microtime());
    $customClaims = [
      'login_id' => $loginId
    ];

    try {
      if (!$token = JWTAuth::attempt($credentials, $customClaims)) {
        return $this->respondForbidden($this->invalidCredentialMessage);
      }
    } catch (JWTException $e) {
      return $this->respondInternal('Could not create user token.');
    }

    $account = JWTAuth::setToken($token)->toUser();
    $account->login_id = $loginId;

    if ($after = $this->afterLogin) {
      $after($account, $request);
    }

    $account->token = $token;

    return $this->respondWithItem($account, $this->transformer);
  }


  /**
   * @param Authenticatable|Account $user
   * @param Request $request
   * @return
   */
  public function loginWithUser(Authenticatable $user, Request $request)
  {

    if ($after = $this->afterLogin) {
      $after($user, $request);
    }


    $token = JWTAuth::fromUser($user);
    
    $user->token = $token;

    return $this->respondWithItem($user, $this->transformer);
  }

  public function registerValidate($username, $code)
  {
    $this->validateUsername($username);

    $this->validateCode($code);
  }

  public function validateCode($code)
  {
    if (LOCAL_APP) return true;

    $smsData = SmsManager::retrieveSentInfo(null);
    $message = false;
    if (!$smsData || ($smsData['deadline_time'] < time())) {
      SmsManager::forgetSentInfo();
      $message = '验证码已失效';
    } else if ($smsData['code'] != $code) {
      $message = '验证码不正确';
    }

    if ($message) {
      return $this->respondUnprocessable($message);
    }
    return true;
  }

  /**
   * @param $username
   * @return mixed
   */
  public function validateUsername($username)
  {
    if ($this->recheckUsername($username)) {
      return $this->respondUnprocessable($this->existsUsernameMessage);
    }
    return false;
  }

  /**
   * @param Request $request
   * @return mixed
   */
  public function addUser(Request $request)
  {
    $account = $this->create($request);
    if ($after = $this->afterRegister) {
      $after($account, $request);
    }

    $token = JWTAuth::fromUser($account);
    $account->token = $token;

    return $this->respondWithItem($account, $this->transformer);
  }
}