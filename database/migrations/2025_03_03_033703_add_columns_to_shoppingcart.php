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
        Schema::table('shoppingcart', function (Blueprint $table) {
            //注文コードカラム（デフォルトはnull）
            $table->string('code')->default("");
            //金額カラム（unsigned()でマイナス禁止、デフォルト値を 0 に設定）
            $table->integer('price_total')->unsigned()->default(0);
            //個数カラム（unsigned()でマイナス禁止、デフォルト値を 0 に設定）
            $table->integer('qty')->unsigned()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shoppingcart', function (Blueprint $table) {
            $table->dropColumn('code');
            $table->dropColumn('price_total');
            $table->dropColumn('qty');
        });
    }
};
