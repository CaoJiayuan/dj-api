<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBannersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('banners', function (Blueprint $table) {
      $table->increments('id');
      $table->unsignedInteger('shop_id')->index()->comment('商店id');
      $table->string('image')->comment('图片');
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
    Schema::drop('banners');
  }
}
