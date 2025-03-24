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
        Schema::table('rpg_battle_states', function (Blueprint $table) {
            $table->json('enemy_drops_json_data')->nullable()->after('enemies_json_data');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rpg_battle_states', function (Blueprint $table) {
            $table->dropColumn('enemy_drops_json_data');
        });
    }
};
