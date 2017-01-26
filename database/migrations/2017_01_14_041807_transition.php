<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Transition extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transition', function (Blueprint $table) {
            $this->generateTable($table);
            $table->unsignedInteger('amount_money');
            $table->text('sign_data');
            $table->unsignedInteger('transition_id');
            $table->unsignedInteger('provider_payment_id');

            $table->foreign('provider_payment_id')->references('id')->on('provider_payment');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transition');
    }
}
