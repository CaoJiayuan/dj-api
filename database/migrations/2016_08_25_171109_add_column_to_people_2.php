<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddColumnToPeople2 extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('people', function (Blueprint $table) {
      $table->timestamp('reviewed_at')->nullable()->default(null)->comment('审核通过时间');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('people', function (Blueprint $table) {
      $table->dropColumn('reviewed_at');
    });
  }
}
