<?php
/**
 * CreditRepository.php
 * Date: 16/5/13
 * Time: 下午3:29
 */

namespace App\Repositories;


use App\Entity\CreditRecord;
use Illuminate\Database\Eloquent\Model;

class CreditRepository extends Repository
{

  public function change($change, $msg)
  {
    $change = (int)$change;
    
  }

  /**
   * @return Model
   */
  public function getModel()
  {
    return app(CreditRecord::class);
  }
}