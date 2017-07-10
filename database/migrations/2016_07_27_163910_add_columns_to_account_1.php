<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddColumnsToAccount1 extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('accounts', function (Blueprint $table) {
      $table->integer('balance')->default(0)->comment('余额(分)');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('accounts', function (Blueprint $table) {
      $table->dropColumn('balance');
    });
  }
}
