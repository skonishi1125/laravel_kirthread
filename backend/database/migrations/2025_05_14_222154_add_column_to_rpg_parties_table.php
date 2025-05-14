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
        Schema::table('rpg_parties', function (Blueprint $table) {
            $table->bigInteger('allocated_hp')->unsigned()->default(0)->after('value_hp');
            $table->bigInteger('allocated_ap')->unsigned()->default(0)->after('value_ap');
            $table->bigInteger('allocated_str')->unsigned()->default(0)->after('value_str');
            $table->bigInteger('allocated_def')->unsigned()->default(0)->after('value_def');
            $table->bigInteger('allocated_int')->unsigned()->default(0)->after('value_int');
            $table->bigInteger('allocated_spd')->unsigned()->default(0)->after('value_spd');
            $table->bigInteger('allocated_luc')->unsigned()->default(0)->after('value_luc');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rpg_parties', function (Blueprint $table) {
            $table->dropColumn('allocated_hp');
            $table->dropColumn('allocated_ap');
            $table->dropColumn('allocated_str');
            $table->dropColumn('allocated_def');
            $table->dropColumn('allocated_int');
            $table->dropColumn('allocated_spd');
            $table->dropColumn('allocated_luc');
        });
    }
};
