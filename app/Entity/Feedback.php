<?php

namespace App\Entity;

/**
 * App\Entity\Feedback
 *
 * @property integer $id
 * @property integer $account_id 对应账户id
 * @property string $content 内容
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Entity\Account $account
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Feedback whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Feedback whereAccountId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Feedback whereContent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Feedback whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Feedback whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Feedback extends BaseEntity
{
  protected $fillable = ['account_id', 'content'];

  public function account()
  {
    return $this->belongsTo(Account::class);
  }
}
