<?php
/**
 * QrUtil.php
 * Date: 16/7/26
 * Time: 下午4:11
 * Created by Caojiayuan
 */

namespace App\Utils;


use App\Traits\JsonHelper;
use QRencode;

class QrUtil
{
  use JsonHelper;

  /**
   * @param string|array $text
   * @param int $size
   * @param int $margin
   * @param null $logo
   * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
   */
  public static function png($text, $size = 6, $margin = 1, $logo = null)
  {
    $_this = new static;
    if ($_this->shouldBeJson($text)) {
      $text = $_this->morphToJson($text);
    }

    $enc = QRencode::factory(QR_ECLEVEL_L, $size, $margin);
    ob_start();
    $enc->encodePNG($text, false, false);
    $image = ob_get_clean();
    if ($logo) {
      try {
        $lg = file_get_contents($logo);
      } catch (\Exception $e) {
        return response($image, 200, [
          'Content-Type' => 'image/png',
        ]);
      }
      $QR = imagecreatefromstring($image);
      $logo = imagecreatefromstring($lg);
      $QR_width = imagesx($QR);
      $QR_height = imagesy($QR);
      $logo_width = imagesx($logo);
      $logo_height = imagesy($logo);
      $logo_qr_width = $QR_width / 5;
      $scale = $logo_width / $logo_qr_width;
      $logo_qr_height = $logo_height / $scale;
      $from_width = ($QR_width - $logo_qr_width) / 2;
      $from_height = ($QR_height - $logo_qr_height) / 2;
      imagecopyresampled($QR, $logo, $from_width, $from_height, 0, 0, $logo_qr_width,
        $logo_qr_height, $logo_width, $logo_height);
      imagepng($QR);
      $image = ob_get_clean();
    }

    return response($image, 200, [
      'Content-Type' => 'image/png',
    ]);
  }
}