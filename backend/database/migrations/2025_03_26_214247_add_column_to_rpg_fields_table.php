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
        Schema::table('rpg_fields', function (Blueprint $table) {
            $table->unsignedInteger('required_clears')->default(0)->after('difficulty')->comment('フィールド開放に必要となる、他のクリアしたフィールド数');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rpg_fields', function (Blueprint $table) {
            $table->dropColumn('required_clears');
        });
    }
};
