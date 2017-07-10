<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateInsuranceTypesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('insurance_types', function (Blueprint $table) {
      $table->increments('id');
      $table->string('name', 100);
      $table->unsignedInteger('insure_id')->comment('对应商家ID');
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
    Schema::drop('insurance_types');
  }
}
