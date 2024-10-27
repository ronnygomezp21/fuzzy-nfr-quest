<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->string('nfr');
            $table->string('variable');
            $table->string('feedback1');
            $table->string('value');
            $table->string('feedback2');
            $table->string('recomend');
            $table->string('feedback3');
            $table->string('validar');
            $table->unsignedBigInteger('game_room_id');
            $table->timestamps();

            $table->foreign('game_room_id')->references('id')->on('game_rooms')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
