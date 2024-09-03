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
        Schema::create('rpg_roles', function (Blueprint $table) {
            $table->id();
            $table->string('class');
            $table->string('class_kana');
            $table->bigInteger('growth_hp')->unsigned();
            $table->bigInteger('growth_ap')->unsigned();
            $table->bigInteger('growth_str')->unsigned();
            $table->bigInteger('growth_def')->unsigned();
            $table->bigInteger('growth_int')->unsigned();
            $table->bigInteger('growth_spd')->unsigned();
            $table->bigInteger('growth_luc')->unsigned();
            $table->string('portrait_image_path');
            $table->string('cutscene_image_path');
            $table->string('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rpg_roles');
    }
};
