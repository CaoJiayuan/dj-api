<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWithdrawsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('withdraws', function (Blueprint $table) {
      $table->increments('id');
      $table->unsignedInteger('account_id')->comment('账户ID');
      $table->unsignedMediumInteger('amount')->comment('金额');
      $table->unsignedInteger('credit_card_id')->comment('银行卡ID');
      $table->unsignedTinyInteger('status')->default(0)->comment('提现状态');
      $table->string('withdrawer')->nullable()->comment('提现操作人');
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
    Schema::drop('withdraws');
  }
}
