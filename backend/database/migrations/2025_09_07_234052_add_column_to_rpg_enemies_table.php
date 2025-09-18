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
        Schema::table('rpg_enemies', function (Blueprint $table) {
            $table->string('image_aspect_ratio')->nullable()->after('portrait_image_path')->comment('敵画像の比率');
            //
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rpg_enemies', function (Blueprint $table) {
            $table->dropColumn('image_aspect_ratio');
        });
    }
};
