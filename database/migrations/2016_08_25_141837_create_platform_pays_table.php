<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePlatformPaysTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('platform_pays', function (Blueprint $table) {
      $table->increments('id');
      $table->unsignedInteger('driver_id');
      $table->unsignedInteger('trip_id');
      $table->unsignedInteger('amount');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::drop('platform_pays');
  }
}
