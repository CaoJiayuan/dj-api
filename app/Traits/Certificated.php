<?php
/**
 * Certed.php
 * Date: 16/7/21
 * Time: 下午2:25
 * Created by Caojiayuan
 */

namespace App\Traits;


use App\Entity\Account;

trait Certificated
{
  /**
   * @param $type
   * @param Account $user
   * @return \App\Entity\Person|null
   */
  public function hasCertificated($type = CERT_CAR, $user = null)
  {
    if (!$user) {
      /** @var Account $user */
      $user = $this->getUser();
    }

    $cert = $user->cert($type);

    if (!$cert) {
      return null;
    }
    
    return $cert->status == CERT_REVIEWED ? $cert : null;
  }
}