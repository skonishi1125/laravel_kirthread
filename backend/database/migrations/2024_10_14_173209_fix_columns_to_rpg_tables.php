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
          $table->renameColumn('rpg_role_id', 'role_id');
          $table->renameColumn('rpg_weapon_id', 'equip_item_id');

        });
        Schema::table('rpg_battle_states', function (Blueprint $table) {
          $table->renameColumn('user_id', 'savedata_id');
        });
        Schema::table('rpg_party_learned_skills', function (Blueprint $table) {
          $table->renameColumn('rpg_party_id', 'party_id');
          $table->renameColumn('rpg_skill_id', 'skill_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('rpg_parties', function (Blueprint $table) {
        $table->renameColumn('savedata_id', 'user_id');
        $table->renameColumn('role_id', 'rpg_role_id');
        $table->renameColumn('equip_item_id', 'rpg_weapon_id');
      });
      Schema::table('rpg_battle_states', function (Blueprint $table) {
        $table->renameColumn('savedata_id', 'user_id' );
      });
      Schema::table('rpg_party_learned_skills', function (Blueprint $table) {
        $table->renameColumn('party_id', 'rpg_party_id');
        $table->renameColumn('skill_id', 'rpg_skill_id');
      });
    }
};
