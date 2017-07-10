<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * 行驶证表
 * Class CreateDrivingLicensesTable
 */
class CreateDrivingLicensesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('driving_licenses', function (Blueprint $table) {
      $table->increments('id');
      $table->unsignedInteger('people_id')->comment('认证人id');
      $table->string('car_id',20)->comment('车牌号码');
      $table->string('brand',20)->nullable()->comment('车辆品牌');
      $table->string('car_color',20)->nullable()->comment('车辆颜色');
      $table->string('dr_image')->comment('认证图片');
      $table->unsignedTinyInteger('car_model')->default(CAR_MODEL_A)->comment('车辆类型');
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
    Schema::drop('driving_licenses');
  }
}
