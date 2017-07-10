<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePaymentsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('payments', function (Blueprint $table) {
      $table->increments('id');
      $table->unsignedTinyInteger('type')->default(PAYMENT_RECHARGE);
      $table->unsignedInteger('cash')->default(0);
      $table->unsignedInteger('credit')->default(0);
      $table->unsignedInteger('account_id');
      $table->unsignedInteger('with')->nullable();
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
    Schema::drop('payments');
  }
}
