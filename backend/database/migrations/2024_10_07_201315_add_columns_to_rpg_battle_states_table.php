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
            $table->json('items_json_data')->after('players_json_data');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rpg_battle_states', function (Blueprint $table) {
            $table->dropColumn('items_json_data');
        });
    }
};
