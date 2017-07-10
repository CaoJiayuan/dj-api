<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddColumnToLocalPayRuleApplies extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('local_pay_rule_applies', function (Blueprint $table) {
      $table->unsignedTinyInteger('limit')->default(2)->comment('最大无里程费公里数');

    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('local_pay_rule_applies', function (Blueprint $table) {
      $table->dropColumn('limit');
    });
  }
}
