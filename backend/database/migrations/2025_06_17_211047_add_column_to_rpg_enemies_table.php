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
        Schema::table('rpg_enemies', function (Blueprint $table) {
            $table->boolean('has_pattern')->default(false)->after('is_boss')->comment('行動パターンを持つかどうか');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rpg_enemies', function (Blueprint $table) {
            $table->dropColumn('has_pattern');
        });
    }
};
