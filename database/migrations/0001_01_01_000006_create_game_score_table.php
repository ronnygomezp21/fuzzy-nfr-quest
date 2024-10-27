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
        Schema::create('game_score', function (Blueprint $table) {
            $table->id();
            //$table->unsignedBigInteger('game_id');
            $table->unsignedBigInteger('user_id');
            $table->decimal('score', 5, 2); 
            $table->json('answered_questions');
            $table->timestamps();

            //$table->foreign('game_id')->references('id')->on('games')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_score');
    }
};
