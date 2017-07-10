<?php
/**
 * BaseController.php
 * Date: 16/5/16
 * Time: 上午11:22
 */

namespace App\Http\Controllers\V1;


use Api\StarterKit\Controllers\ApiController;
use App\Entity\Account;
use Illuminate\Http\Request;
use JWTAuth;

class BaseController extends ApiController
{
  
  public $withToken = true;

  /**
   * @var Request
   */
  public $request;

  protected $me = null;
  
  public function __construct(Request $request)
  {

    $this->request = $request;
  }

  /**
   * @return Account|mixed
   */
  public function getUser()
  {
    if ($this->me != null) return $this->me;
    
    try {
      $token = JWTAuth::getToken();
      $user = JWTAuth::parseToken()->authenticate();
    } catch (\Exception $e) {
      return $this->respondForbidden('登录信息验证失败,请重新登录');
    }

    if ($this->withToken) {
      $user->token = $token->get()->get();
    }
    return $this->me = $user;
  }

  public function inputGet($key, $default = null)
  {
    return $this->request->get($key, $default);
  }


  public function inputAll()
  {
    return $this->request->all();
  }


  public function validateRequest(array $role)
  {
    $this->validate($this->request, $role);
  }
}