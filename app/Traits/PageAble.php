<?php
/**
 * PageAble.php
 * Date: 16/6/13
 * Time: 下午4:53
 */

namespace App\Traits;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

trait PageAble
{
  /**
   * @param Request $request
   * @param int $size
   * @return array
   */
  public function getPageParam($request, $size = 20)
  {
    return [$request->get('page') ?: 1, $request->get('size') ?: $size];
  }


  /**
   * @param Request $request
   * @param Collection $collection
   * @return Collection
   */
  public function forPage(Request $request, Collection $collection)
  {
    list($page, $size) = $this->getPageParam($request);
    
    return $collection->forPage($page, $size);
  }
}