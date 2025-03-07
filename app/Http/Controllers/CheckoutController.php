<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class CheckoutController extends Controller
{
    //注文内容の確認ページを表示
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

            /*$total += 800;
            $carriage_cost = 800;*/
        }

        return view('checkout.index', compact('cart', 'total', 'carriage_cost'));
    }

    //Stripe APIに支払い情報を送信し、Stripeの決済ページにリダイレクトさせる
    public function store(Request $request)
    {
        //ユーザーのIDを元にこれまで追加したカートの中身を$cart変数に保存
        //content()→ そのカートに入っている商品一覧を取得
        $cart = Cart::instance(Auth::user()->id)->content();

        //送料が発生するかどうかを判定するフラグ）
        $has_carriage_cost = false;

        foreach ($cart as $product) {
            //$product->options->carriage が true なら(送料があれば) $has_carriage_cost を true に設定
            //送料がかかる商品が1つでもあれば true になる
            if ($product->options->carriage) {
                $has_carriage_cost = true;
            }
        }

        //APIキーが反映されているか確認
        //dd(env('STRIPE_SECRET'));

        /*シークレットキーは機密情報なのでソースコードに直接記述してはいけない
        ⇒StripeクラスのsetApiKey()メソッドに環境変数STRIPE_SECRETの値を渡す*/
        Stripe::setApiKey('sk_test_51Qyb3IQ9d9JAAZz6zXOcVB9e5bsxqdb3dT23BqgQO63Js4bPvYol5RcVVcU9SkBX076AynPfusMkADo5t7oHPn7200mSHjwB5v');

        $line_items = [];

        foreach ($cart as $product) {
            $line_items[] = [
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $product->name,
                    ],
                    'unit_amount' => $product->price,
                ],
                'quantity' => $product->qty,
            ];
        }

        if ($has_carriage_cost) {
            $line_items[] = [
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => '送料',
                    ],
                    'unit_amount' => env('CARRIAGE'),
                ],
                'quantity' => 1,
            ];
        }

        //stripe-phpライブラリが提供するSessionクラスのcreate()メソッドを使い、Stripeに送信する支払い情報をセッションとして作成
        $checkout_session = Session::create([
            //支払い対象となる商品(カート内の商品および送料（送料は必要な場合）)
            'line_items' => $line_items,
            //支払いモード(一回限りの支払い)
            'mode' => 'payment',
            //決済成功時のリダイレクト先URL(決済完了後の案内ページ)
            'success_url' => route('checkout.success'),
            //決済キャンセル時のリダイレクト先URL(注文内容の確認ページ)
            'cancel_url' => route('checkout.index'),
        ]);

        //Stripe側でセッションが保持する情報を取得し、適切な決済ページを作成・表示
        return redirect($checkout_session->url);
    }

    //決済完了後の案内ページを表示
    public function success()
    {
        //shoppingcart テーブルの全データを取得
        $user_shoppingcarts = DB::table('shoppingcart')->get();
        //ユーザーのカート数（注文履歴数）を取得
        $number = DB::table('shoppingcart')->where('instance', Auth::user()->id)->count();

        //count() で現在のカートの件数を取得し、新しいカートID用に +1 する
        $count = $user_shoppingcarts->count();
        $count += 1;
        $number += 1;

        //カートの中身を取得
        $cart = Cart::instance(Auth::user()->id)->content();
        //合計金額 (price_total)、商品数 (qty_total)、送料 (has_carriage_cost) を初期化
        $price_total = 0;
        $qty_total = 0;
        $has_carriage_cost = false;

        foreach ($cart as $c) {
            //各商品の合計金額と個数を計算
            $price_total += $c->qty * $c->price;
            $qty_total += $c->qty;
            //送料 (carriage) がある商品（送料あり）が含まれていたらtrueにする
            if ($c->options->carriage) {
                $has_carriage_cost = true;
            }
        }

        //もし送料ありの場合は、合計金額に送料を＋
        if($has_carriage_cost) {
            $price_total += env('CARRIAGE');
        }

        //カートの内容をデータベースに保存
        //$count をカートIDとして 注文履歴を保存
        Cart::instance(Auth::user()->id)->store($count);

        //DB::table('shoppingcart')では、データベース内のshoppingcartテーブルへのアクセスを行い、その後where()を使ってユーザーのIDとカート数$countを使い、先ほど作成したカートのデータを更新
        DB::table('shoppingcart')->where('instance', Auth::user()->id)
            ->where('number', null)
            ->update(
                [
                    //注文コード (code) をランダム生成
                   'code' => substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwxyz'), 0, 10),
                   //注文番号 (number) をセット
                    'number' => $number,
                    //合計金額 
                    'price_total' => $price_total,
                    'qty' => $qty_total,
                    //購入フラグ (buy_flag = true) を設定
                    'buy_flag' => true,
                    //更新日時 (updated_at) を現在時刻に更新
                    'updated_at' => date("Y/m/d H:i:s")
                ]
            );

        //カートを空にする
        Cart::instance(Auth::user()->id)->destroy();

        //カート一覧ページへリダイレクト（注文完了後の画面遷移）
        return view('checkout.success');
    }
}
