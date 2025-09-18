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
        Schema::table('rpg_libraries', function (Blueprint $table) {
            $table->unsignedInteger('required_clears')->nullable()->default(null)->comment('書籍開放に必要となる、他のクリアしたフィールド数')->change();
            $table->unsignedInteger('required_clear_field_id')->nullable()->default(null)->comment('書籍開放に必要となるフィールドID')->after('required_clears');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rpg_libraries', function (Blueprint $table) {
            $table->unsignedInteger('required_clears')->after('content')->default(0)->comment('書籍開放に必要となる、他のクリアしたフィールド数')->change();
            $table->dropColumn('required_clear_field_id');
        });
    }
};
