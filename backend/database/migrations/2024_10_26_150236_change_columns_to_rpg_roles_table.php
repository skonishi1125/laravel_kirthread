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
        Schema::table('rpg_roles', function (Blueprint $table) {
            $table->renameColumn('class_kana', 'class_japanese');
            $table->string('default_name')->after('class_japanese');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rpg_roles', function (Blueprint $table) {
            $table->renameColumn('class_japanese', 'class_kana');
            $table->dropColumn('default_name');
        });
    }
};
