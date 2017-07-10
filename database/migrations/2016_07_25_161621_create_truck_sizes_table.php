<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTruckSizesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('truck_sizes', function (Blueprint $table) {
      $table->increments('id');
      $table->unsignedInteger('truck_type_id')->comment('货车类型ID');
      $table->unsignedSmallInteger('width')->default(0)->comment('宽(厘米)');
      $table->unsignedSmallInteger('height')->default(0)->comment('高(厘米)');
      $table->unsignedSmallInteger('length')->default(0)->comment('长(厘米)');
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
    Schema::drop('truck_sizes');
  }
}
