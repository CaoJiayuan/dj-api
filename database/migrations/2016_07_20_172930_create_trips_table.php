<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTripsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('trips', function (Blueprint $table) {
      $table->increments('id');
      $table->unsignedInteger('account_id')->index()->comment('对应用户id');
      $table->string('start')->nullable()->comment('起点');
      $table->unsignedInteger('city_id')->default(0)->index()->comment('起点城市id');
      $table->string('destination')->nullable()->comment('终点');
      $table->timestamp('time')->default(\Carbon\Carbon::now())->comment('出发时间');
      $table->timestamp('start_at')->nullable()->comment('行程开始时间');
      $table->timestamp('finish_at')->nullable()->comment('行程结束时间');
      $table->float('longitude')->default(0)->comment('经度');
      $table->float('latitude')->default(0)->comment('纬度');
      $table->float('longitude2')->default(0)->comment('目的地经度');
      $table->float('latitude2')->default(0)->comment('目的地纬度');
      $table->unsignedInteger('trip_fee')->default(0)->comment('预估费用');
      $table->unsignedInteger('truck_size_id')->nullable()->default(0)->comment('货车型号ID');
      $table->unsignedTinyInteger('population')->default(1)->comment('乘车人数');
      $table->unsignedTinyInteger('rest_sets')->default(4)->comment('剩余空位');
      $table->unsignedTinyInteger('status')->default(TRIP_PUBLISHED)->comment('行程状态');
      $table->unsignedTinyInteger('type')->index()->default(TYPE_LOCAL)->comment('行程类型');
      $table->unsignedTinyInteger('role')->index()->default(ROLE_PASSENGER)->comment('角色类型');
      $table->unsignedTinyInteger('car_model')->nullable()->index()->default(0)->comment('车类型');
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
    Schema::drop('trips');
  }
}
