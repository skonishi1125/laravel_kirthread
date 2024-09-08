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
        Schema::create('rpg_preset_appearing_enemies', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('field_id')->unsigned();
            $table->bigInteger('stage_id')->unsigned();
            $table->bigInteger('enemy_id')->unsigned();
            $table->bigInteger('number')->unsigned()->default(1)->comment('複数出現させる場合、その数');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rpg_preset_appearing_enemies');
    }
};
