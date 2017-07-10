<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTripOrdersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('trip_orders', function (Blueprint $table) {
      $table->increments('id');
      $table->unsignedInteger('journey_order_id')->default(0)->comment('顺风车订单ID');
      $table->unsignedInteger('trip_id')->index()->comment('行程ID');
      $table->unsignedInteger('passenger_id')->index()->comment('乘客ID');
      $table->unsignedInteger('driver_id')->index()->comment('司机ID');
      $table->unsignedMediumInteger('distance')->default(0)->comment('里程（单位米');
      $table->unsignedSmallInteger('duration')->default(0)->comment('时长（单位分钟）');
      $table->unsignedSmallInteger('start_fee')->default(0)->comment('起步价（单位分）');
      $table->unsignedMediumInteger('distance_fee')->default(0)->comment('里程费（单位米）');
      $table->unsignedMediumInteger('duration_fee')->default(0)->comment('时长费（单位分）');
      $table->unsignedInteger('cash_amount')->default(0)->comment('现金支付价格');
      $table->unsignedInteger('credit_amount')->default(0)->comment('积分支付');
      $table->unsignedInteger('amount')->default(0)->comment('总价格');
      $table->unsignedInteger('status')->default(ORDER_ORDERED)->comment('订单状态(0-已创建,1-已完成,2-已取消)');
      $table->string('cancel_reason')->nullable()->comment('取消原因');
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
    Schema::drop('trip_orders');
  }
}
