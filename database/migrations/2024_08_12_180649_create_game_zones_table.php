<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('game_zones', function (Blueprint $table) {
            $table->id('gameId');
            $table->string('gameName');
            $table->string('gameWebLink');
            $table->string('thumbnail')->nullable();
            $table->integer('rewardCoins');
            $table->string('category');
            $table->enum('visibility',['hidden','show']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_zones');
    }
};
