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
        // rpg_skill_requirementsテーブルの作成
        Schema::create('rpg_skill_requirements', function (Blueprint $table) {
            $table->id();
            $table->integer('acquired_skill_id')->unsigned()->comment('取得対象とするスキルのID');
            $table->integer('requirement_skill_id')->unsigned()->nullable()->comment('取得に必要なスキルのID');
            $table->integer('requirement_skill_level')->unsigned()->nullable()->comment('取得に必要なスキルのLv');
            $table->integer('requirement_party_level')->unsigned()->default(1)->comment('取得に必要なパーティメンバーのLv');
            $table->timestamps();
        });

        Schema::table('rpg_parties', function (Blueprint $table) {
            $table->integer('freely_status_point')->unsigned()->default(4)->after('total_exp')->comment('自由に割り振れる残ステータスポイント');
            $table->integer('freely_skill_point')->unsigned()->default(1)->after('freely_status_point')->comment('自由に割り振れる残スキルポイント');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rpg_skill_requirements', function (Blueprint $table) {
            Schema::dropIfExists('rpg_skill_requirements');
        });

        Schema::table('rpg_parties', function (Blueprint $table) {
          $table->dropColumn('freely_status_point');
          $table->dropColumn('freely_skill_point');
        });
    }
};
