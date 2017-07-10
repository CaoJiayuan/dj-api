<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddColumnToTrips3 extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('trips', function (Blueprint $table) {
      $table->unsignedMediumInteger('distance')->default(0)->comment('里程（单位米');
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
      $table->dropColumn('distance');
    });
  }
}
