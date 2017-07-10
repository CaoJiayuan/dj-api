<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAccountsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('accounts', function (Blueprint $table) {
      $table->increments('id');
      $table->string('username', 11)->unique()->comment('手机号');
      $table->string('nickname', 60)->nullable()->comment('昵称');
      $table->string('password', 60)->comment('密码');
      $table->string('avatar')->nullable()->comment('头像');
      $table->string('channel_id')->nullable()->comment('设备channel id');
      $table->unsignedInteger('city_id')->nullable()->comment('城市id');
      $table->tinyInteger('sex')->default(SEX_MALE)->comment('用户性别');
      $table->tinyInteger('receivable')->default(RECEIVE_CLOSE)->comment('司机可否接受推送');
      $table->timestamp('receive_at')->nullable()->default(\Carbon\Carbon::now())->comment('司机接受推送的时间');
      $table->integer('credits')->defualt(0)->comment('积分');
      $table->unsignedTinyInteger('device')->defualt(DEVICE_ANDROID)->comment('设备类型');
      $table->rememberToken();
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
    Schema::drop('accounts');
  }
}
