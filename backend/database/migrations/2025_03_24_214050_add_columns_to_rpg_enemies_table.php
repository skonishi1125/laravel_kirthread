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
            $table->boolean('drop_item_id')->after('drop_money')->nullable()->default(null)->comment('ドロップするアイテムID');
            $table->boolean('drop_weapon_id')->after('drop_item_id')->nullable()->default(null)->comment('ドロップする武器のID');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rpg_enemies', function (Blueprint $table) {
            $table->dropColumn('drop_item_id');
            $table->dropColumn('drop_weapon_id');
        });
    }
};
