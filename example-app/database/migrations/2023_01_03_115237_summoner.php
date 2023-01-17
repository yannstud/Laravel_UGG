<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('summoner', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('puuid');
            $table->string('tiers');
            $table->string('rank');
            $table->integer('leaguePoints');
            $table->integer('win');
            $table->integer('losses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
