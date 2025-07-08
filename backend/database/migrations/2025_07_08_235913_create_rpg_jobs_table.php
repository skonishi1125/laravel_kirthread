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
        Schema::create('rpg_jobs', function (Blueprint $table) {
            $table->id();
            $table->integer('savedata_id')->unsigned();
            $table->bigInteger('total_count')->unsigned()->default(0);
            $table->integer('grade')->unsigned()->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rpg_jobs');
    }
};
