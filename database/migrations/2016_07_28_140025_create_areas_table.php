<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAreasTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('areas', function (Blueprint $table) {
      $table->increments('id');
      $table->unsignedInteger('city_id')->comment('城市id');
      $table->string('name')->comment('区县名称');
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
    Schema::drop('areas');
  }
}
