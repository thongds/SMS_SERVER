<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SubscribeHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscribe_history', function (Blueprint $table) {
           $this->generateTable($table);
           $table->unsignedInteger('user_id');
            $table->unsignedInteger('subscribe_type_id');
            $table->unsignedInteger('transition_id');

            $table->foreign('user_id')->references('id')->on('user');
            $table->foreign('subscribe_type_id')->references('id')->on('subscribe_type');
            $table->foreign('transition_id')->references('id')->on('transition');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('subscribe_history');
    }
}
