<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToPeople extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('people', function (Blueprint $table) {
        $table->boolean('profitable')->default(false)->comment('是否可获取补贴');
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
        $table->dropColumn('profitable');
      });
    }
}
