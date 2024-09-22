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
            $table->renameColumn('type', 'skill_category');
            $table->integer('target_range')->unsigned()->after('skill_category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {        
        Schema::table('rpg_skills', function (Blueprint $table) {
            $table->renameColumn('skill_category', 'type');
            $table->dropColumn('target_range');
        });

    }
};
