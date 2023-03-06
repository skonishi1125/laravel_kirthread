<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReactionIconsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reaction_icons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->comment('リアクションの呼び名');
            $table->boolean('is_picture_icon')->default(false)->comment('リアクションが画像でできているものかどうか.');
            $table->string('url')->nullable()->comment('画像の場合、その格納先URL');
            $table->string('description')->nullable()->comment('メモ書き用');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reaction_icons');
    }
}
