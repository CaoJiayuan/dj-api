<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * 保险商家表
 * Class CreateInsuresTable
 */
class CreateInsuresTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('insures', function (Blueprint $table) {
      $table->increments('id');
      $table->string('name')->comment('保险商家名');
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
    Schema::drop('insures');
  }
}
