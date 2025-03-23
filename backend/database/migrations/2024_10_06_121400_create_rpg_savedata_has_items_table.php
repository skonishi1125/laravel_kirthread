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
        Schema::create('rpg_savedata_has_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('savedata_id')->unsigned();
            $table->bigInteger('item_id')->unsigned();
            $table->bigInteger('possession_number')->unsigned()->comment('所持数');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rpg_savedata_has_items');
    }
};
