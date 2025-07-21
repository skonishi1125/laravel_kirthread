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
        Schema::create('rpg_boards', function (Blueprint $table) {
            $table->id();
            $table->string('message', 20);
            $table->integer('savedata_id')->unsigned();
            $table->boolean('is_spoiled')->default(false)->comment('ネタバレフラグ');
            $table->boolean('is_banned')->default(false)->comment('不適切な投稿などがあったとき、非表示対応とするフラグ');
            $table->timestamps();
            $table->softDeletes()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rpg_boards');
    }
};
