<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddColumnsToAccount2 extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('accounts', function (Blueprint $table) {
      $table->boolean('working')->default(false)->comment('是否上班');
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
      $table->dropColumn('working');
    });
  }
}
