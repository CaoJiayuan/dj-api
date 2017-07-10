<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * 驾驶证表
 * Class CreateDriverLicensesTable
 */
class CreateDriverLicensesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('driver_licenses', function (Blueprint $table) {
      $table->increments('id');
      $table->unsignedInteger('people_id')->comment('认证人id');
      $table->string('d_image')->comment('认证图片');
      $table->unsignedTinyInteger('type')->default(CERT_CAR)->index()->comment('认证类型');
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
    Schema::drop('driver_licenses');
  }
}
