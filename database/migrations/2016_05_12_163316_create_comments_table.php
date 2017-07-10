<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCommentsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('comments', function (Blueprint $table) {
      $table->increments('id');
      $table->unsignedTinyInteger('score')->default(0)->comment('评分');
      $table->unsignedTinyInteger('type')->index()->default(TYPE_LOCAL)->comment('订单类型');
      $table->unsignedInteger('trip_order_id')->index()->comment('评论的订单id');
      $table->unsignedInteger('account_id')->comment('对应用户id');
      $table->string('comment')->nullable()->comment('评论内容');
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
    Schema::drop('comments');
  }
}
