<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsJourneyPayRules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('journey_pay_rules', function (Blueprint $table) {
            $table->unsignedTinyInteger('limit2')->default(0)->comment('距离限制');
            $table->unsignedInteger('less_price')->default(0)->comment('少于限制距离的费用');
            $table->unsignedInteger('type')->default(TYPE_JOURNEY)->comment('打车类型');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('journey_pay_rules', function (Blueprint $table) {
            $table->dropColumn('limit2');
            $table->dropColumn('less_price');
            $table->dropColumn('type');
        });
    }
}
