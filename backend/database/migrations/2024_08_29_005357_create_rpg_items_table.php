<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // DBファサードをインポート

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rpg_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedMediumInteger('price')->default(0); // 価格はマイナスにならないので、符号なし(unsigined)で扱う
            $table->string('description')->nullable();
            $table->boolean('is_buyable')->default(true);
            $table->timestamps();
        });

        // デフォルトのデータを追加
        DB::table('rpg_items')->insert([
            [
                'name' => 'ヒールポーション',
                'price' => 100,
                'description' => '仲間1人のHPを50ポイント回復します',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'マナウォーター',
                'price' => 300,
                'description' => '仲間1人のMPを20ポイント回復します',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'キュアリーフ',
                'price' => 150,
                'description' => '仲間1人の状態異常を回復します',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '金塊',
                'price' => 3000,
                'description' => '売るとお金になる',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rpg_items');
    }
};
