<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHotSongTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hot_song', function (Blueprint $table) {
            $this->generateTable($table);
            $table->string('name');
            $table->string('duration');
            $table->string('avatar');
            $table->string('avatar_path');
            $table->string('subtitle_source');
            $table->string('subtitle_source_path');
            $table->unsignedInteger('category_id');
            $table->unsignedInteger('language_id');
            $table->unsignedInteger('singer_id');
            $table->unsignedInteger('subtitle_type_id');
            $table->unsignedInteger('song_type_id');
            $table->unsignedInteger('song_detail_id');

            $table->string('song_source');
            $table->string('song_source_path');

            $table->foreign('category_id')->references('id')->on('category');
            $table->foreign('language_id')->references('id')->on('language');
            $table->foreign('singer_id')->references('id')->on('singer');
            $table->foreign('subtitle_type_id')->references('id')->on('subtitle_type');
            $table->foreign('song_type_id')->references('id')->on('song_type');
            $table->foreign('song_detail_id')->references('id')->on('song_detail');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hot_song');
    }
}
