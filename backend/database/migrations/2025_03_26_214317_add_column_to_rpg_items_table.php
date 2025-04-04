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
        Schema::table('rpg_items', function (Blueprint $table) {
            $table->unsignedInteger('required_clears')->default(0)->after('is_battle_available')->comment('ショップに販売されるために必要となるクリアしたフィールドの数');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rpg_items', function (Blueprint $table) {
            $table->dropColumn('required_clears');
        });
    }
};
