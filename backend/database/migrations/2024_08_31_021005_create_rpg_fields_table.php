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
        Schema::create('rpg_fields', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedInteger('difficulty')->nullable();
            $table->timestamps();
        });

                // デフォルトのデータを追加
                DB::table('rpg_fields')->insert([
                  [
                      'name' => '草原',
                      'difficulty' => 1,
                      'created_at' => now(),
                      'updated_at' => now(),
                  ],
                  [
                      'name' => '海',
                      'difficulty' => 2,
                      'created_at' => now(),
                      'updated_at' => now(),
                  ],
                  [
                      'name' => '砂地',
                      'difficulty' => 2,
                      'created_at' => now(),
                      'updated_at' => now(),
                  ],
                  [
                      'name' => '火山',
                      'difficulty' => 2,
                      'created_at' => now(),
                      'updated_at' => now(),
                  ],
                  [
                      'name' => '氷雪地帯',
                      'difficulty' => 3,
                      'created_at' => now(),
                      'updated_at' => now(),
                  ],
                  [
                      'name' => '古城',
                      'difficulty' => 5,
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
        Schema::dropIfExists('rpg_fields');
    }
};
