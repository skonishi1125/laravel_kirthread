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
            $table->integer('heal_type')->unsigned()->default(0)->after('attack_type')->comment('0:該当無し 1:HP 2:AP');
            $table->boolean('is_battle_available')->after('is_buyable')->comment('戦闘中に使用できるアイテムかどうか');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rpg_items', function (Blueprint $table) {
            $table->dropColumn('heal_type');
        });
    }
};
