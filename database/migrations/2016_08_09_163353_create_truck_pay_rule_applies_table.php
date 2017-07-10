<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTruckPayRuleAppliesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('truck_pay_rule_applies', function (Blueprint $table) {
      $table->increments('id');
      $table->unsignedInteger('user_id')->comment('申请人ID');
      $table->unsignedInteger('city_id')->default(0)->comment('对应城市ID(默认0)');
      $table->unsignedInteger('truck_size_id')->comment('对应货车尺寸ID');
      $table->unsignedInteger('init_price')->default(0)->comment('起步价, 分');
      $table->unsignedInteger('distance_price')->default(0)->comment('里程费/公里');
      $table->unsignedTinyInteger('status')->default(CERT_UNREVIEWED)->index()->comment('认证状态');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::drop('truck_pay_rule_applies');
  }
}
