<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBalanceRecordsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('balance_records', function (Blueprint $table) {
      $table->increments('id');
      $table->unsignedInteger('account_id')->index()->comment('对应用户id');
      $table->integer('changes')->comment('余额变化量');
      $table->string('cause')->nullable()->comment('余额变化原因');
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
    Schema::drop('balance_records');
  }
}
