<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BuySongHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buy_song_history', function (Blueprint $table) {
            $this->generateTable($table);
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('song_detail_id');
            $table->unsignedInteger('transition_id');

            $table->foreign('user_id')->references('id')->on('user');
            $table->foreign('song_detail_id')->references('id')->on('song_detail');
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
        Schema::dropIfExists('buy_song_history');
    }
}
