<?php
/**
 * ModelHelper.php
 * Date: 16/5/20
 * Time: 上午10:21
 */

namespace App\Traits;


use Illuminate\Database\Eloquent\Model;
use UnexpectedValueException;

trait ModelHelper
{
  public $forceCopy = false;

  public function copy($model, $data)
  {
    if (!is_object($model)) {
      $model = app($model);
    }

    if (!$model instanceof Model) {
      throw new UnexpectedValueException('Except instance of '
        . Model::class . ', instance of ' . get_class($model) . ' giving.');
    }

    $cantCopy = property_exists(get_class($model), 'cantCopy') && $model->cantCopy != null ? $model->cantCopy : [];

    $fillable = $model->getFillable();

    $key = $model->getKeyName();

    if (array_key_exists($key, $data)) {
      if ($find = $model->find($data[$key])) {
        $model = $find;
      }
    }
    if ($this->forceCopy) {
      $cantCopy = [];
    }

    foreach ((array)$fillable as $column) {
      if (array_key_exists($column, $data) && null != $data[$column] && !in_array($column, $cantCopy)) {
        $model->$column = $data[$column];
      }
    }

    $model->save();

    return $model->find($model->getKey());
  }
}