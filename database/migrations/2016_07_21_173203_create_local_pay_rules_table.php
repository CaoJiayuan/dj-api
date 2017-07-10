<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLocalPayRulesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('local_pay_rules', function (Blueprint $table) {
      $table->increments('id');
      $table->unsignedInteger('car_model_id');
      $table->unsignedInteger('city_id')->default(0)->comment('对应城市ID(默认0)');
      $table->unsignedInteger('init_price')->default(0)->comment('起步价, 分');
      $table->unsignedInteger('duration_price')->comment('时长费/分钟');
      $table->unsignedInteger('distance_price')->comment('里程费/公里');
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
    Schema::drop('local_pay_rules');
  }
}
