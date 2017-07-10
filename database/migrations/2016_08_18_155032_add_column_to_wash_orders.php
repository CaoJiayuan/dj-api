<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddColumnToWashOrders extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('wash_orders', function (Blueprint $table) {
      $table->unsignedTinyInteger('status')->default(ORDER_FINISHED)->comment('订单状态');

    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('wash_orders', function (Blueprint $table) {
      $table->dropColumn('status');
    });
  }
}
