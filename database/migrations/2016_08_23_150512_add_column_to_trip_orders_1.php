<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddColumnToTripOrders1 extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('trip_orders', function (Blueprint $table) {
      $table->unsignedMediumInteger('back_fee')->default(0)->comment('返程费');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('trip_orders', function (Blueprint $table) {
      $table->dropColumn('back_fee');

    });
  }
}
