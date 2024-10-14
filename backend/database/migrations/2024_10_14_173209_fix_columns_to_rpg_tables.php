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
          $table->renameColumn('user_id', 'savedata_id');
        });
        Schema::table('rpg_battle_states', function (Blueprint $table) {
          $table->renameColumn('user_id', 'savedata_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('rpg_parties', function (Blueprint $table) {
        $table->renameColumn('savedata_id', 'user_id');
      });
      Schema::table('rpg_battle_states', function (Blueprint $table) {
        $table->renameColumn('savedata_id', 'user_id' );
      });
    }
};
