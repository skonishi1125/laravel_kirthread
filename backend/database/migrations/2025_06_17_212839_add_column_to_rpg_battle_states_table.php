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
            $table->bigInteger('current_turn')->default(1)->unsigned()->after('enemy_drops_json_data')->comment('現在のターン数');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rpg_battle_states', function (Blueprint $table) {
            $table->dropColumn('current_turn');
        });
    }
};
