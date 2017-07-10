<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFeedbacksTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('feedbacks', function (Blueprint $table) {
      $table->increments('id');
      $table->unsignedInteger('account_id')->comment('对应账户id');
      $table->string('content')->comment('内容');
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
    Schema::drop('feedbacks');
  }
}
