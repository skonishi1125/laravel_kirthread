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
            $table->integer('lv1_ap_cost')->unsigned()->after('lv1_percent');
            $table->integer('lv2_ap_cost')->unsigned()->after('lv2_percent');
            $table->integer('lv3_ap_cost')->unsigned()->after('lv3_percent');
            $table->integer('attack_type')->unsigned()->after('available_role_id')->comment('1:物理 2:魔法');
            $table->renameColumn('type', 'effect_type');
            $table->integer('target_range')->unsigned()->after('effect_type')->comment('1:単体 2:全体');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rpg_skills', function (Blueprint $table) {
            $table->dropColumn('lv1_ap_cost');
            $table->dropColumn('lv2_ap_cost');
            $table->dropColumn('lv3_ap_cost');
            $table->dropColumn('attack_type');
            $table->renameColumn('effect_type', 'type');
            $table->dropColumn('target_range');
        });
    }
};
