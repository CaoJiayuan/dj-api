<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateChauffeurPayRuleAppliesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('chauffeur_pay_rule_applies', function (Blueprint $table) {
      $table->increments('id');
      $table->unsignedInteger('user_id')->comment('申请人ID');
      $table->unsignedInteger('city_id')->default(0);
      $table->unsignedTinyInteger('limit')->default(4)->comment('最大无里程费公里数');
      $table->unsignedTinyInteger('limit2')->default(20)->comment('最大固定里程费公里数');
      $table->unsignedInteger('distance_fee')->default(3000)->comment('固定里程费');
      $table->unsignedInteger('distance_price')->default(300)->comment('超出部分里程费');
      $table->unsignedTinyInteger('status')->default(CERT_UNREVIEWED)->index()->comment('认证状态');
      $table->unsignedInteger('init_price')->default(1000)->comment('起步价');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::drop('chauffeur_pay_rule_applies');
  }
}
