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
        Schema::table('game_score', function (Blueprint $table) {
            $table->unsignedBigInteger('game_room_id')->after('user_id');
            $table->foreign('game_room_id')->references('id')->on('game_rooms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('game_score', function (Blueprint $table) {
            $table->dropForeign(['game_room_id']);
            $table->dropColumn('game_room_id');
        });
    }
};
