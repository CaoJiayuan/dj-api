<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateInsureOrdersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('insure_orders', function (Blueprint $table) {
      $table->increments('id');
      $table->unsignedInteger('account_id')->index();
      $table->unsignedInteger('insure_id')->comment('保险商家ID');
      $table->unsignedInteger('amount')->nullable()->default(0)->comment('报价');
      $table->string('insurances')->comment('险种, 以;隔开' );
      $table->string('dr_image')->comment('行驶证照片');
      $table->string('car_id',20)->comment('车牌号码');
      $table->unsignedTinyInteger('handled')->default(INSURE_REVIEWING)->comment('保险状态');
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
    Schema::drop('insure_orders');
  }
}
