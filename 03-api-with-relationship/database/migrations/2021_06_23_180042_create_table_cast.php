<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCast extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cast', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->integer('name');
            $table->integer('movie_id');
            $table->foreign('movie_id')
                ->references('movie')
                ->on('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cast');
    }
}
