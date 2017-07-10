<?php
/**
 * Repository.php
 * Date: 16/5/12
 * Time: 下午5:10
 */

namespace App\Repositories;

use App\Entity\Account;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use JWTAuth;

abstract class Repository
{
  /**
   * @var Model|Builder
   */
  protected $model;


  public function __construct()
  {
    $model = $this->getModel();

    if (!is_object($model)) {
      $model = app($model);
    }

    $this->model = $model;
  }

  /**
   * @return Model
   */
  abstract public function getModel();

  /**
   * @return Account|null
   */
  public function getUser()
  {
    try {
      $JWTAuth = JWTAuth::parseToken();
    } catch (\Exception $e) {
      return null;
    }

    return $JWTAuth->authenticate();
  }

}