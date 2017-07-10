<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToDriving extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('driving_licenses', function (Blueprint $table) {
        $table->integer('truck_size_id')->default(0)->comment('货车尺寸ID');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('driving_licenses', function (Blueprint $table) {
        $table->dropColumn('truck_size_id');
      });
    }
}
