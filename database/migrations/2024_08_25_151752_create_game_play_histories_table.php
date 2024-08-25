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
        Schema::create('game_play_histories', function (Blueprint $table) {
            $table->id();
            $table->string('gameId');
            $table->string('userId');
            $table->string('minutePlayed')->default(1);
            $table->enum('claimed',[1,0])->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_play_histories');
    }
};
