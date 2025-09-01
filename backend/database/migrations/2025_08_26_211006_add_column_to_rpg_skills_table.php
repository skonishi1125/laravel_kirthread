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
            $table->boolean('is_slow')->after('is_first')->default(false)->comment('後攻行動するスキルであるかどうかを判定する。');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rpg_skills', function (Blueprint $table) {
            $table->dropColumn('is_slow');
        });
    }
};
