<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCitiesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('cities', function (Blueprint $table) {
      $table->increments('id');
      $table->unsignedInteger('province_id')->comment('省份id');
      $table->string('name', '10')->comment('城市名称');
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
    Schema::drop('cities');
  }
}
