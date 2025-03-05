<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ShoppingCart extends Model
{
    use HasFactory;

    //モデルにするテーブルにshoppingcartテーブルを指定
    protected $table = 'shoppingcart';

    //指定された ユーザーID ($user_id) に紐づく注文情報（ショッピングカート）を取得し、整形して返す処理をしている
    public static function getCurrentUserOrders($user_id)
    {
        //shoppingcart テーブルからデータを取得
        //where("instance", "{$user_id}") → instance カラムが $user_id のデータのみ取得
        $shoppingcarts = DB::table('shoppingcart')->where("instance", "{$user_id}")->get();

        //注文情報を格納するための空の配列 を準備
        $orders = [];

        //$shoppingcarts の 1件ずつ を $order に代入して、$orders[] に整形して追加
        foreach ($shoppingcarts as $order) {
            $orders[] = [
                'id' => $order->number,//注文番号
                'created_at' => $order->updated_at,//更新日時
                'total' => $order->price_total,//合計金額
                //instance カラムは ユーザーID を指しているので、それを User::find() で検索し、name を取得
                'user_name' => User::find($order->instance)->name,//ユーザー名
                'code' => $order->code//注文コード
            ];
        }

        //$orders 配列を返して、注文情報の一覧として利用できるようにする
        return $orders;
    }
}
