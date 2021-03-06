<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToDrivingLicenses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('driving_licenses', function (Blueprint $table) {
        $table->unsignedInteger('city_id')->default(0)->comment('城市ID');
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
        $table->dropColumn('city_id');
      });
    }
}
