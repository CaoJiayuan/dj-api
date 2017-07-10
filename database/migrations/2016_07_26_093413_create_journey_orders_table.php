<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateJourneyOrdersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('journey_orders', function (Blueprint $table) {
      $table->increments('id');
      $table->unsignedInteger('account_id')->index()->comment('司机ID');
      $table->unsignedInteger('amount')->comment('总价');
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
    Schema::drop('journey_orders');
  }
}
