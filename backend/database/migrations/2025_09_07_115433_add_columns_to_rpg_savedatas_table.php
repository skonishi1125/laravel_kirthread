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
        Schema::table('rpg_savedatas', function (Blueprint $table) {
            // 不要カラムの整理
            $table->dropColumn('unspent_skill_points');
            $table->dropColumn('play_time');
            $table->dropColumn('save_timestamp');
            $table->dropColumn('difficulty');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rpg_savedatas', function (Blueprint $table) {
            $table->unsignedInteger('unspent_skill_points')->default(0)->after('money');
            $table->unsignedBigInteger('play_time')->default(0)->after('unspent_skill_points');
            $table->timestamp('save_timestamp')->nullable()->after('play_time');
            $table->string('difficulty')->default('Normal')->after('save_timestamp');
        });
    }
};
