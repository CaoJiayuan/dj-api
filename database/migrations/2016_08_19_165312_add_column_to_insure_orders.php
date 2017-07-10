<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddColumnToInsureOrders1 extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('insure_orders', function (Blueprint $table) {
      $table->string('address')->nullable();
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
      $table->dropColumn('address');
    });
  }
}
