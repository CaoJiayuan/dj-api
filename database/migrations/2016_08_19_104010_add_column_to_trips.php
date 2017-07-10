<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddColumnToTrips extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('trips', function (Blueprint $table) {
      $table->timestamp('time_end')->default(\Carbon\Carbon::now()->addHour(2))->comment('预定结束时间');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('trips', function (Blueprint $table) {
      $table->dropColumn('time_end');
    });
  }
}
