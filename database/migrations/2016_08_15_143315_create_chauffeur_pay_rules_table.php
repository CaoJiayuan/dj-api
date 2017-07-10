<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * 酒后代驾规则
 * Class CreateChauffeurPayRulesTable
 */
class CreateChauffeurPayRulesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('chauffeur_pay_rules', function (Blueprint $table) {
      $table->increments('id');
      $table->unsignedInteger('city_id')->default(0);
      $table->unsignedTinyInteger('limit')->default(10)->comment('最大无里程费公里数');
      $table->unsignedTinyInteger('limit2')->default(10)->comment('最大固定里程费公里数');
      $table->unsignedInteger('distance_fee')->default(4900)->comment('固定里程费');
      $table->unsignedInteger('distance_price')->default(3000)->comment('十公里里程费');
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
    Schema::drop('chauffeur_pay_rules');
  }
}
