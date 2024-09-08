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
        Schema::create('rpg_exps', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('level')->unsigned()->default(1);
            $table->bigInteger('total_exp')->unsigned()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rpg_exps');
    }
};
