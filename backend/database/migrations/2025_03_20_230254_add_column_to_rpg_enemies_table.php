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
            $table->boolean('is_boss')->after('description')->default(false)->comment('ステージのボスかどうかの判定フラグ');
            //
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rpg_enemies', function (Blueprint $table) {
            $table->dropColumn('is_boss');
        });
    }
};
