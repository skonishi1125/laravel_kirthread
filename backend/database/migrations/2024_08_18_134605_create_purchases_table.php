<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->bigIncrements('id'); // intよりも格納できる値が広い
            $table->bigInteger('user_id')->unsigned(); // unsigned: 符号無し
            $table->date('date');
            $table->double('price', 8, 2); // 有効桁数と小数点以下桁数の指定
            $table->string('description');
            $table->timestamps();
            // 外部キー制約
            // purchasesテーブルのuser_idを、usersテーブルのidに紐付けることになる ("user_id" references "id" on "users" table.)
            $table->foreign('user_id')->references('id')->on('users'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchases');
    }
}
