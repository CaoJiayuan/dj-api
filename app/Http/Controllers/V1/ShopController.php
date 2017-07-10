<?php
/**
 * ShopController.php
 * Date: 16/5/16
 * Time: 上午11:38
 */

namespace App\Http\Controllers\V1;


use App\Repositories\ShopRepository;
use App\Traits\PageAble;
use Illuminate\Http\Request;

class ShopController extends BaseController
{

  use PageAble;
  /**
   * @var ShopRepository
   */
  private $repository;

  public function __construct(ShopRepository $repository, Request $request)
  {
    parent::__construct($request);
    $this->repository = $repository;
  }

  public function code()
  {

  }

  public function getList()
  {

    $this->validate($this->request, [
      'lng' => 'required',
      'lat' => 'required',
    ]);
    $lng = $this->inputGet('lng');
    $lat = $this->inputGet('lat');


    if (!$page = $this->inputGet('page')) {
      $page = 1;
    }

    if (!$size = $this->inputGet('size')) {
      $size = 20;
    }

    return $this->respondWithCollection($this->repository->getList($lng, $lat, $page, $size));
  }

  public function comments()
  {
    $this->validate($this->request, [
      'id' => 'required',
    ]);

    return $this->respondWithCollection($this->repository->getComments($this->inputGet('id')));

  }

  public function detail()
  {
    $this->validate($this->request, ['id' => 'required']);

    $shop = $this->repository->getDetail($this->inputGet('id'));

    return $this->respondWithItem($shop);
  }

  public function orders(ShopRepository $repository)
  {
    list($page, $size) = $this->getPageParam($this->request);

    return $this->respondWithCollection($repository->orders(ROLE_TYPE, $page, $size));
  }
}