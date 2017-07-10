<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateShopsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('shops', function (Blueprint $table) {
      $table->increments('id');
      $table->string('shop_name')->comment('店名');
      $table->unsignedInteger('people_id')->index()->comment('认证人id');
      $table->string('s_image')->comment('认证图片');
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
    Schema::drop('shops');
  }
}
