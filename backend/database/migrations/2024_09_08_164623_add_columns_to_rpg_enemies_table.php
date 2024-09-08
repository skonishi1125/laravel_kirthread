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
            $table->bigInteger('exp')->unsigned()->after('value_luc')->default(0);
            $table->bigInteger('drop_money')->unsigned()->after('exp')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rpg_enemies', function (Blueprint $table) {
            $table->dropColumn('exp');
            $table->dropColumn('drop_money');
        });
    }
};
