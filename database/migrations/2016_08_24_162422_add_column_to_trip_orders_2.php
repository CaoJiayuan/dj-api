<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddColumnToTripOrders2 extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('trip_orders', function (Blueprint $table) {
      $table->unsignedMediumInteger('pre_pay')->default(0)->comment('预付款');

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
      $table->dropColumn('pre_pay');
    });
  }
}
