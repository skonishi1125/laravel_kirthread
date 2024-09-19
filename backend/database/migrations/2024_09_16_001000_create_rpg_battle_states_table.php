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
        Schema::create('rpg_battle_states', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->string('session_id')->unique();
            $table->json('players_json_data');
            $table->json('enemies_json_data');
            $table->integer('current_field_id')->unsigned()->comment('現在プレイ中のフィールドID');
            $table->integer('current_stage_id')->unsigned()->comment('現在プレイ中のステージID');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rpg_battle_states');
    }
};
