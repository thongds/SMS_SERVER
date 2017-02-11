<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSourceToSongDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('song_detail', function (Blueprint $table) {
            $table->string('song_source');
            $table->string('song_source_path');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('song_detail', function (Blueprint $table) {
            $table->dropColumn('song_source');
            $table->dropColumn('song_source_path');
        });
    }
}
