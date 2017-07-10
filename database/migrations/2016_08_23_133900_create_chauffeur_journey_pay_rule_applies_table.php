<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateChauffeurJourneyPayRuleAppliesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('chauffeur_journey_pay_rule_applies', function (Blueprint $table) {
      $table->increments('id');
      $table->unsignedInteger('user_id')->comment('申请人ID');
      $table->unsignedInteger('city_id')->default(0);
      $table->unsignedSmallInteger('limit')->default(500)->comment('公里数界限');
      $table->unsignedMediumInteger('less_price')->default(12000)->comment('低于公里数界限里程价');
      $table->unsignedMediumInteger('more_price')->default(10000)->comment('高于公里数界限里程价');
      $table->unsignedMediumInteger('less_price_back')->default(10000)->comment('低于公里数界限返程价');
      $table->unsignedMediumInteger('more_price_back')->default(8000)->comment('高于公里数界限返程价');
      $table->unsignedTinyInteger('status')->default(CERT_UNREVIEWED)->index()->comment('认证状态');
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
    Schema::drop('chauffeur_journey_pay_rule_applies');
  }
}
