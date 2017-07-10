<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateComplainsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('complains', function (Blueprint $table) {
      $table->increments('id');
      $table->unsignedInteger('driver_id')->comment('司机ID');
      $table->unsignedInteger('account_id')->comment('投诉人ID');
      $table->string('content')->comment('投诉内容');
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
    Schema::drop('complains');
  }
}
