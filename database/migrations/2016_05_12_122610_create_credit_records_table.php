<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * 积分记录表
 * Class CreateCreditRecordsTable
 */
class CreateCreditRecordsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('credit_records', function (Blueprint $table) {
      $table->increments('id');
      $table->unsignedInteger('account_id')->index()->comment('对应用户id');
      $table->mediumInteger('changes')->comment('积分变化量');
      $table->string('cause')->nullable()->comment('积分变化原因');
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
    Schema::drop('credit_records');
  }
}
