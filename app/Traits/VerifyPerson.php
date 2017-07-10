<?php
/**
 * VerifyPerson.php
 * Date: 16/5/18
 * Time: 下午8:10
 */

namespace App\Traits;

use App\Entity\Person;

trait VerifyPerson
{
  /**
   * @param $name
   * @param $id
   * @param $phone
   * @param $type
   * @return Person
   */
  public function verifyPerson($name, $id, $phone, $type)
  {
    $account = $this->getUser();
    return Person::create([
      'name'       => $name,
      'phone'      => $phone,
      'type'       => $type,
      'id_number'  => $id,
      'account_id' => $account->id,
    ]);
  }
}