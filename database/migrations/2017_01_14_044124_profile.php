<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Profile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profile', function (Blueprint $table) {
            $this->generateTable($table);
            $table->string('email');
            $table->string('avatar');
            $table->unsignedInteger('subscribe_type_id');
            $table->unsignedInteger('user_id');

            $table->foreign('subscribe_type_id')->references('id')->on('subscribe_type');
            $table->foreign('user_id')->references('id')->on('user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('profile');
    }
}
