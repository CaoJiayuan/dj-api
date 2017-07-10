<?php
/**
 * JsonHelper.php
 * Date: 16/7/26
 * Time: 下午4:43
 * Created by Caojiayuan
 */

namespace App\Traits;


use ArrayObject;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;

trait JsonHelper
{
  /**
   * Morph the given content into JSON.
   *
   * @param  mixed   $content
   * @return string
   */
  public function morphToJson($content)
  {
    if ($content instanceof Jsonable) {
      return $content->toJson();
    }

    return json_encode($content);
  }

  /**
   * Determine if the given content should be turned into JSON.
   *
   * @param  mixed  $content
   * @return bool
   */
  public function shouldBeJson($content)
  {
    return $content instanceof Jsonable ||
    $content instanceof ArrayObject ||
    $content instanceof JsonSerializable ||
    is_array($content);
  }
}