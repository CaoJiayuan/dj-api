<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddColumnToJourneyOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('journey_orders', function (Blueprint $table) {
        $table->unsignedTinyInteger('status')->default(ORDER_ORDERED)->comment('订单状态');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('journey_orders', function (Blueprint $table) {
        $table->dropColumn('status');
      });
    }
}
