<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //ユーザーのIDを元にこれまで追加したカートの中身を$cart変数に保存
        //content()→ そのカートに入っている商品一覧を取得
        $cart = Cart::instance(Auth::user()->id)->content();

        //合計金額を計算するための変数（初期値 0）
        $total = 0;
        //送料が発生するかどうかを判定するフラグ
        $has_carriage_cost = false;
        //送料の金額を保存（初期値 0）
        $carriage_cost = 0;

        //$cart の 各商品を $c としてループ処理
        foreach ($cart as $c) {
            //$c->qty * $c->price で 商品ごとの金額を計算し、$total に加算
            $total += $c->qty * $c->price;
            //$c->options->carriage が true なら(送料があれば) $has_carriage_cost を true に設定
            //送料がかかる商品が1つでもあれば true になる
            if ($c->options->carriage) {
                $has_carriage_cost = true;
            }
        }

        //送料がある場合
        if($has_carriage_cost) {
            //合計金額＋送料800円（.envで設定）
            $total += env('CARRIAGE');
            //$carriage_costに800円を設定
            $carriage_cost = env('CARRIAGE');
        }

        //compact関数で変数をcarts.indexビューに渡す
        return view('carts.index', compact('cart', 'total', 'carriage_cost'));
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //ユーザーのIDを元にカートのデータを作成し、add()関数を使って送信されたデータを元に商品を追加する
        Cart::instance(Auth::user()->id)->add(
            [
                'id' => $request->id, 
                'name' => $request->name, 
                'qty' => $request->qty, 
                'price' => $request->price, 
                'weight' => $request->weight, 
                'options' => [
                    'image' => $request->image,
                    //formから送信された送料の有無をカートに保存
                    'carriage' => $request->carriage,
                ]
            ] 
        );

        //商品の個別ページへとリダイレクト
        return to_route('carts.index');
    }
}