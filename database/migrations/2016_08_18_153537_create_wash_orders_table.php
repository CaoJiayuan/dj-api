<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWashOrdersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('wash_orders', function (Blueprint $table) {
      $table->increments('id');
      $table->unsignedInteger('people_id')->comment('商家认证人ID');
      $table->string('order_no')->comment('交易编号');
      $table->unsignedInteger('account_id');
      $table->unsignedMediumInteger('amount')->default(0)->comment('总价格');
      $table->unsignedInteger('cash_amount')->default(0)->comment('现金支付价格');
      $table->unsignedInteger('credit_amount')->default(0)->comment('积分支付');
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
    Schema::drop('wash_orders');
  }
}
