<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddColumnToInsureOrders extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('insure_orders', function (Blueprint $table) {
//      $table->string('order_no');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('insure_orders', function (Blueprint $table) {
//      $table->dropColumn('order_no');
    });
  }
}
