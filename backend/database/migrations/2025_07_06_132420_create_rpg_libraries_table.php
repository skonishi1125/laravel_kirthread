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
        Schema::create('rpg_libraries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('book_category')->unsigned()->comment('0: 戦術学論 1:魔物図譜 2:歴史神話学');
            $table->text('content')->comment('本の内容。HTMLタグを付与した状態で記述する');
            $table->unsignedInteger('required_clears')->default(0)->comment('書籍開放に必要となる、他のクリアしたフィールド数');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rpg_libraries');
    }
};
