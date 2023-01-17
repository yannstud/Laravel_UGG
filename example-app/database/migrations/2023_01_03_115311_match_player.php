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
        Schema::create('match_player', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username');
            $table->string('championName');
            $table->string('teamId');
            $table->string('summonerSpell1Id');
            $table->string('summonerSpell2Id');
            $table->integer('kills');
            $table->integer('deaths');
            $table->integer('assists');
            $table->integer('kda');
            $table->integer('totalMinionKilled');
            $table->integer('perks10');
            $table->integer('perks11');
            $table->integer('perks12');
            $table->integer('perks13');
            $table->integer('perks20');
            $table->integer('perks21');
            $table->integer('perks22');
            $table->integer('item0');
            $table->integer('item1');
            $table->integer('item2');
            $table->integer('item3');
            $table->integer('item4');
            $table->integer('item5');
            $table->foreignId('match_id')->references('id')->on('match');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('match_player');
    }
};
