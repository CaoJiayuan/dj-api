<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateJourneyPayRuleAppliesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('journey_pay_rule_applies', function (Blueprint $table) {
      $table->increments('id');
      $table->unsignedInteger('user_id')->comment('申请人ID');
      $table->unsignedInteger('car_model_id')->default(0);
      $table->unsignedInteger('city_id')->default(0)->comment('对应城市ID(默认0)');
      $table->unsignedInteger('limit')->default(10)->comment('最大无里程费公里数');
      $table->unsignedInteger('init_price')->default(0)->comment('起步价, 分');
      $table->unsignedInteger('duration_price')->default(0)->comment('时长费/分钟');
      $table->unsignedInteger('distance_price')->default(0)->comment('里程费/公里');
      $table->unsignedInteger('status')->default(CERT_UNREVIEWED)->index()->comment('认证状态');
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
    Schema::drop('journey_pay_rule_applies');
  }
}
