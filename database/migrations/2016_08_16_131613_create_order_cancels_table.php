<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrderCancelsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('order_cancels', function (Blueprint $table) {
      $table->increments('id');
      $table->unsignedInteger('account_id');
      $table->unsignedTinyInteger('type')->index()->default(TYPE_LOCAL);
      $table->unsignedTinyInteger('role')->index()->default(ROLE_PASSENGER);
      $table->unsignedInteger('order_id');
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
    Schema::drop('order_cancels');
  }
}
