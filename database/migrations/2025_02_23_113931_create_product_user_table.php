<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_user', function (Blueprint $table) {
            $table->id();
            //cascadeOnDelete()メソッド=参照先のデータ（今回の場合はusersテーブルまたはproductsテーブルのデータ）が削除されると参照元のデータ（今回の場合はproduct_userテーブルのデータ）も同時に削除されるようになる
            //あるユーザーや商品が削除されると、そのユーザーや商品に紐づくお気に入り登録もすべて解除される
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_user');
    }
};
