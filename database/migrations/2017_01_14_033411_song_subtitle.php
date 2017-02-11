<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SongSubtitle extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('song_subtitle', function (Blueprint $table) {
//            $this->generateTable($table);
//            $table->string('source');
//            $table->unsignedInteger('subtitle_type_id');
//            $table->foreign('subtitle_type_id')->references('id')->on('subtitle_type');
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::dropIfExists('song_subtitle');
    }
}
