<?php
/**
 * RelationEmpty.php
 * Date: 16/6/8
 * Time: 上午9:03
 */

namespace App\Vendor\Illuminate;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class RelationEmpty extends Relation
{

  public function __construct(Model $model)
  {
    parent::__construct($model->newQuery(), $model);
  }

  /**
   * Set the base constraints on the relation query.
   *
   * @return void
   */
  public function addConstraints()
  {
  }

  /**
   * Set the constraints for an eager load of the relation.
   *
   * @param  array $models
   * @return void
   */
  public function addEagerConstraints(array $models)
  {
  }

  /**
   * Initialize the relation on a set of models.
   *
   * @param  array $models
   * @param  string $relation
   * @return array
   */
  public function initRelation(array $models, $relation)
  {
  }

  /**
   * Match the eagerly loaded results to their parents.
   *
   * @param  array $models
   * @param  \Illuminate\Database\Eloquent\Collection $results
   * @param  string $relation
   * @return array
   */
  public function match(array $models, Collection $results, $relation)
  {
  }

  /**
   * Get the results of the relationship.
   *
   * @return mixed
   */
  public function getResults()
  {
    return [];
  }
}