<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddColumnToInsureOrders2 extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('insure_orders', function (Blueprint $table) {
      $table->unsignedInteger('pay_amount')->default(0);
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
      $table->dropColumn('pay_amount');
    });
  }
}
