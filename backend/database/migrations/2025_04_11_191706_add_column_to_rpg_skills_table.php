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
        Schema::table('rpg_skills', function (Blueprint $table) {
            $table->boolean('is_target_enemy')->after('target_range')->default(true)->comment('敵を対象とするスキルかどうかを判定する。');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rpg_skills', function (Blueprint $table) {
            $table->dropColumn('is_target_enemy');
        });
    }
};
