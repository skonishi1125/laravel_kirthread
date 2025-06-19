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
        Schema::create('rpg_enemy_action_patterns', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('enemy_id')->unsigned();
            $table->bigInteger('turn_count')->unsigned();
            $table->boolean('is_use_skill');
            $table->bigInteger('skill_id')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rpg_enemy_action_patterns');
    }
};
