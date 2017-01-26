<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DefaultImage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('default_image', function (Blueprint $table) {
            $this->generateTable($table);
            $table->string('name');
            $table->string('avatar');
            $table->string('avatar_path');
            $table->string('logo');
            $table->string('logo_path');
            $table->string('content');
            $table->string('content_path');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('default_image');
    }
}
