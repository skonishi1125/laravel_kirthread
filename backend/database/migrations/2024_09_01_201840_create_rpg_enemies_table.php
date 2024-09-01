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
        Schema::create('rpg_enemies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->bigInteger('appear_field_id')->unsigned();
            $table->bigInteger('value_hp')->unsigned();
            $table->bigInteger('value_ap')->unsigned();
            $table->bigInteger('value_str')->unsigned();
            $table->bigInteger('value_def')->unsigned();
            $table->bigInteger('value_int')->unsigned();
            $table->bigInteger('value_spd')->unsigned();
            $table->bigInteger('value_luc')->unsigned();
            $table->string('portrait_image_path');
            $table->string('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rpg_enemies');
    }
};
