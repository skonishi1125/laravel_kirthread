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
        });
    }
};
