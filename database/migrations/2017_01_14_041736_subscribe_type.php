<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SubscribeType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscribe_type', function (Blueprint $table) {
            $this->generateTable($table);
            $table->string('name');
            $table->string('description');
            $table->bigInteger('total_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscribe_type');
    }
}
