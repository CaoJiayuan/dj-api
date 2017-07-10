<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToInsureOrders3 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('insure_orders', function (Blueprint $table) {
        $table->string('order_no')->comment('保险订单号');
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
        $table->dropColumn('order_no');
      });
    }
}
