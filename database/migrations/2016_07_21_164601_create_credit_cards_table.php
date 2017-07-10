<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCreditCardsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('credit_cards', function (Blueprint $table) {
      $table->increments('id');
      $table->string('username', 20);
      $table->unsignedInteger('account_id');
      $table->string('name', 50);
      $table->string('card_id', 20);
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
    Schema::drop('credit_cards');
  }
}
