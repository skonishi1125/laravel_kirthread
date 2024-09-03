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
        Schema::create('rpg_skills', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->bigInteger('available_role_id');
            $table->integer('type')->comment('1: 攻撃系 2: 回復系 3: バフ系 9:その他');
            $table->double('lv1_percent', 8, 2);
            $table->double('lv2_percent', 8, 2);
            $table->double('lv3_percent', 8, 2);
            $table->bigInteger('elemental_id'); // あとで属性テーブルを作る
            $table->string('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rpg_skills');
    }
};
