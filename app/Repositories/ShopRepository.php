<?php
/**
 * ShopRepository.php
 * Date: 16/5/16
 * Time: 上午11:47
 */

namespace App\Repositories;


use Api\StarterKit\Utils\ApiResponse;
use App\Entity\Shop;
use App\Entity\WashOrder;
use App\Traits\VerifyPerson;
use Illuminate\Database\Eloquent\Model;

class ShopRepository extends Repository
{

  use VerifyPerson, ApiResponse;
  /**
   * @var Shop
   */
  protected $model;

  public function getDetail($id)
  {
    /** @var Shop $shop */
    $shop = $this->model->with('account', 'orders')->find($id);

    if ($shop->status == CERT_UNREVIEWED) {
      return $this->respondForbidden("商家{$shop->name}未审核");
    }

    return $shop;
  }

  public function getList($lng, $lat, $page = 1, $limit = 20)
  {
    return $this->model->near($lng, $lat, $page, $limit);
  }

  public function getComments($id)
  {
    $shop = $this->model->find($id);

    return $shop->comments();
  }

  public function verify($data)
  {
    return \DB::transaction(function () use ($data) {

      $user = $this->getUser();
      $shop = $this->model->create([
        'image'      => $data['image'],
        'name'       => $data['name'],
        'longitude'  => $data['lng'],
        'latitude'   => $data['lat'],
        'address'    => $data['address'],
        'account_id' => $user->id,
        'username'   => $data['username'],
      ]);

      return $shop;
    });
  }

  public function orders($role, $page = 1, $size = 2)
  {
    $user = $this->getUser();
    $selects = [
      'wash_orders.created_at',
      'wash_orders.order_no',
      'wash_orders.amount',
      'wash_orders.status',
    ];
    if ($role) { // seller's orders
      $builder = WashOrder::leftJoin('people', 'wash_orders.people_id', '=', 'people.id');

      $builder->where('people.account_id', '=', $user->id);
      $builder->orderBy('wash_orders.id', 'desc');
      $builder->select($selects);
      $builder->forPage($page, $size);
      return $builder->get();
    } else {
      $relation = $user->washOrders();
      $relation->orderBy('wash_orders.id', 'desc');
      $relation->forPage($page, $size);
      $relation->select($selects);
      return $relation->getResults();
    }
  }

  /**
   * @return Model|Shop
   */
  public function getModel()
  {
    return Shop::class;
  }
}