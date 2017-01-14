<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SongDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('song_detail', function (Blueprint $table) {
            $this->generateTable($table);
            $table->string('name');
            $table->string('duration');
            $table->string('avatar');
            $table->unsignedInteger('category_id');
            $table->unsignedInteger('language_id');
            $table->unsignedInteger('singer_id');
            $table->unsignedInteger('song_subtitle_id');

            $table->foreign('category_id')->references('id')->on('category');
            $table->foreign('language_id')->references('id')->on('language');
            $table->foreign('singer_id')->references('id')->on('singer');
            $table->foreign('song_subtitle_id')->references('id')->on('song_subtitle');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('song_detail');
    }
}
