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
        Schema::create('rpg_savedatas', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->unsignedBigInteger('money')->default(0);
            $table->unsignedInteger('unspent_skill_points')->default(0);
            $table->unsignedBigInteger('play_time')->default(0);
            $table->timestamp('save_timestamp')->nullable();
            $table->string('difficulty')->default('Normal');
            $table->timestamps();

            // 外部キー制約をかけるときは、カラム生成が終わってから。
            $table->foreign('user_id')->references('id')->on('users');

        });

        // 本番だとuser_idが外部キー制約に引っかかってエラーが発生するのでコメントアウトしておく。
        // DB::table('rpg_savedatas')->insert([
        //   [
        //       'user_id' => 974, // テストくん
        //       'money' => 1000,
        //       'unspent_skill_points' => 3,
        //       'play_time' => 0,
        //       'save_timestamp' => null,
        //       'difficulty' => 'Normal',
        //       'created_at' => now(),
        //       'updated_at' => now(),
        //   ],
        // ]);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rpg_savedatas');
    }
};
