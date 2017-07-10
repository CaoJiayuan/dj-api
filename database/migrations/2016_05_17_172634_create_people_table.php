<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * 认证人表
 * Class CreatePeopleTable
 */
class CreatePeopleTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('people', function (Blueprint $table) {
      $table->increments('id');
      $table->string('id_number')->nullable()->comment('身份证号');
      $table->string('name', 10)->nullable()->comment('真实姓名');
      $table->string('phone', 15)->nullable()->comment('联系方式');
      $table->unsignedInteger('account_id')->index()->comment('对应用户id');
      $table->unsignedTinyInteger('type')->default(CERT_CAR)->index()->comment('认证类型');
      $table->unsignedTinyInteger('status')->default(CERT_UNREVIEWED)->index()->comment('认证状态');
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
    Schema::drop('people');
  }
}
