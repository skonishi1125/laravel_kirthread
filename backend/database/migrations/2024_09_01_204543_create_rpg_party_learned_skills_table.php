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
        Schema::create('rpg_party_learned_skills', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('rpg_party_id')->unsigned();
            $table->bigInteger('rpg_skill_id')->unsigned();
            $table->bigInteger('skill_level')->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rpg_party_learned_skills');
    }
};
