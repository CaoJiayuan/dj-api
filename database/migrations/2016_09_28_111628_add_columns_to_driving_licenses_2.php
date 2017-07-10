<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToDrivingLicenses2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('driving_licenses', function (Blueprint $table) {
            $table->string('policy_photo')->comment('交强险');
            $table->string('policy_photo_2')->comment('商业险');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('driving_licenses', function (Blueprint $table) {
            $table->dropColumn('policy_photo');
            $table->dropColumn('policy_photo_2');
        });
    }
}
