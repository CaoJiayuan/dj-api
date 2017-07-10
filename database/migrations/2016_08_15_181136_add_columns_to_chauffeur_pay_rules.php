<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddColumnsToChauffeurPayRules extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('chauffeur_pay_rules', function (Blueprint $table) {
      $table->unsignedInteger('init_price')->default(4900)->comment('起步价');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('chauffeur_pay_rules', function (Blueprint $table) {
      $table->dropColumn('init_price');
    });
  }
}
