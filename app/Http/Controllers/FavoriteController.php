<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    //お気に入りに登録する処理
    public function store($product_id)
    {
        //attach()メソッドをつなげ、引数には紐づける対象（商品）のidを渡すことで、そのユーザーと商品を紐づけて中間テーブル（product_userテーブル）にデータを追加してくれる
        Auth::user()->favorite_products()->attach($product_id);

        return back();
    }

    //お気に入りを解除する処理
    public function destroy($product_id)
    {
        //detach()メソッドはattach()メソッドとは逆に、そのユーザーと商品が紐づいたデータを削除する
        Auth::user()->favorite_products()->detach($product_id);

        return back();
    }
}
