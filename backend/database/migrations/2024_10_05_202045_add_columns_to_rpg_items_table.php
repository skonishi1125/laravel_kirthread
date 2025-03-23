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
            $table->integer('attack_type')->unsigned()->after('name')->comment('0:無し 1:物理 2:魔法');
            $table->integer('effect_type')->unsigned()->after('attack_type')->comment('0:特殊系 1:攻撃系 2:治療系 3:バフ系 9:その他');
            $table->integer('target_range')->unsigned()->after('effect_type')->comment('0:自身 1:単体 2:全体');
            $table->boolean('is_percent_based')->after('target_range');
            $table->double('percent', 8, 2)->nullable()->after('is_percent_based');
            $table->integer('fixed_value')->unsigned()->nullable()->after('percent');
            $table->integer('buff_turn')->unsigned()->nullable()->after('fixed_value');
            $table->integer('elemental_id')->unsigned()->after('buff_turn');
            $table->integer('max_possession_number')->unsigned()->after('elemental_id')->comment('所持できる数の最大');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rpg_items', function (Blueprint $table) {
            $table->dropColumn('attack_type');
            $table->dropColumn('effect_type');
            $table->dropColumn('target_range');
            $table->dropColumn('is_percent_based');
            $table->dropColumn('percent');
            $table->dropColumn('fixed_value');
            $table->dropColumn('buff_turn');
            $table->dropColumn('elemental_id');
            $table->dropColumn('max_possession_number');
        });
    }
};
