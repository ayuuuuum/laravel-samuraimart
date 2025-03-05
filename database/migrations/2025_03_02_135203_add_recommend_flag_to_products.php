k <?php

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
        Schema::table('products', function (Blueprint $table) {
            //recommend_flag というカラムを boolean (真偽値: true または false) 型で追加
            //→おすすめ商品かどうかを示すフラグ のような役割を持つ (true = おすすめ, false = 通常商品) ※最初は「おすすめではない」状態 (false) になる
            $table->boolean('recommend_flag')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('recommend_flag');
        });
    }
};
