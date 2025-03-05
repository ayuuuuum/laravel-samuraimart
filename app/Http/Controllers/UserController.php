<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\ShoppingCart;
use Illuminate\Pagination\LengthAwarePaginator;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    //Auth::user()を使い、ユーザー自身の情報を$userに保存し、それをcompact関数でビューへ渡す
    public function mypage()
    {
        $user = Auth::user();

        return view('users.mypage', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //ユーザーの情報をAuth::user()で取得し、ビューへと渡す
        $user = Auth::user();

        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //現在ログインしているユーザーを取得
        $user = Auth::user();

        //?＝三項演算子（＜条件式＞?＜条件式が真の場合＞:＜条件式が偽の場合＞）　リクエストから送られたデータでユーザー情報を更新（ただし、空なら元の値を保持）
        $user->name = $request->input('name') ? $request->input('name') : $user->name;
        $user->email = $request->input('email') ? $request->input('email') : $user->email;
        $user->postal_code = $request->input('postal_code') ? $request->input('postal_code') : $user->postal_code;
        $user->address = $request->input('address') ? $request->input('address') : $user->address;
        $user->phone = $request->input('phone') ? $request->input('phone') : $user->phone;
        $user->update();

        //マイページに遷移
        return to_route('mypage')->with('flash_message', '会員情報を更新しました。');
    }

    public function update_password(Request $request)
    {
        $validatedData = $request->validate([
            'password' => 'required|confirmed',
        ]);

        $user = Auth::user();

        //送信されたリクエスト内のpasswordとpassword_confirmationが同一のものであるかを確認し、同じである場合のみパスワードを暗号化しデータベースへと保存
        if ($request->input('password') == $request->input('password_confirmation')) {
            $user->password = bcrypt($request->input('password'));
            $user->update();
        } else {
            //異なっていた場合は、パスワード変更画面へとリダイレクト
            return to_route('mypage.edit_password');
        }

        return to_route('mypage')->with('flash_message', 'パスワードを更新しました。');
    }

    //パスワード変更画面を表示するedit_passwordアクションを作成
    public function edit_password()
    {
        return view('users.edit_password');
    }

    public function favorite()
    {
        $user = Auth::user();

        //ユーザーがお気に入り登録した商品一覧を取得し、ページネーションで5つずつ表示するよう設定
        $favorite_products = $user->favorite_products()->paginate(5);

        return view('users.favorite', compact('favorite_products'));
    }

    //ユーザーの削除を行うための処理を定義
    public function destroy(Request $request)
    {
        //Auth::user() で 現在ログインしているユーザー を取得
        //delete() メソッドを実行することで ユーザーを削除 する (SoftDeletes を使っている場合は、データベース上では「論理削除（削除フラグが立つ）」される)
        Auth::user()->delete();
        //退会処理が完了した後に、トップページ（'/'）にリダイレクト
        return redirect('/')->with('flash_message', '退会が完了しました。');
    }

    //ユーザーの注文履歴（カート履歴）を取得し、ページネーションを適用して表示する処理
    public function cart_history_index(Request $request)
    {
        //Request $request から page パラメータを取得
        //page が null の場合は 1 を設定（デフォルトで最初のページを表示）
        $page = $request->page != null ? $request->page : 1;
        // 現在ログイン中のユーザーIDを取得
        $user_id = Auth::user()->id;
        //ユーザーの注文履歴を取得（shoppingcart テーブルから instance が $user_id のデータを取得）
        $billings = ShoppingCart::getCurrentUserOrders($user_id);
        //取得した注文履歴の 総数 を取得
        $total = count($billings);
        /*array_slice($billings, ($page - 1) * 15, 15)⇒現在のページに表示する注文履歴 (15 件ずつ表示)
        $total⇒全体の注文数
        15⇒1ページあたりの表示件数
        $page⇒現在のページ番号
        array('path' => $request->url())⇒ページネーションリンクのURL*/
        $billings = new LengthAwarePaginator(array_slice($billings, ($page - 1) * 15, 15), $total, 15, $page, array('path' => $request->url()));

        return view('users.cart_history_index', compact('billings', 'total'));
    }

    //特定の注文履歴の詳細を取得して表示する処理
    public function cart_history_show(Request $request)
    {
        //リクエストから num（注文番号）を取得
        $num = $request->num;
        //現在ログインしているユーザーのID (user_id) を取得
        $user_id = Auth::user()->id;
        //shoppingcartテーブルから、instance（ユーザーID）がuser_idのもの、number（注文番号）が $num のもので最初の1件を取得し変数に代入
        $cart_info = DB::table('shoppingcart')->where('instance', $user_id)->where('number', $num)->get()->first();
        //restore($cart_info->identifier) を使って 過去のカート情報を復元⇒過去のカートが Cart::content() で取得できるようになる
        Cart::instance($user_id)->restore($cart_info->identifier);
        //復元したカートの中身を $cart_contents に保存
        $cart_contents = Cart::content();
        //restoreメソッドを呼び出すとshoppingcartテーブルからデータが消えてしまう⇒storeメソッドでデータを書き戻す処理が必要
        Cart::instance($user_id)->store($cart_info->identifier);
        //一時的に復元したカート情報を削除(カートの内容を履歴参照のためだけに復元し、不要になったら削除するため)
        Cart::destroy();

        //shoppingcart テーブルの number が null のレコード（仮データ）を更新
        DB::table('shoppingcart')->where('instance', $user_id)
            ->where('number', null)
            ->update(
                [
                    //cart_info の内容を使って、正しい注文情報をセット
                    /*storeメソッドで書き戻した際に、codeカラムやnumberカラムなどの一部データの
                    復元ができない制約があるため、以下のupdateメソッドによりデータの書き戻しを行っている*/
                    'code' => $cart_info->code,
                    'number' => $num,
                    'price_total' => $cart_info->price_total,
                    'qty' => $cart_info->qty,
                    'buy_flag' => $cart_info->buy_flag,
                    'updated_at' => $cart_info->updated_at
                ]
            );

        return view('users.cart_history_show', compact('cart_contents', 'cart_info'));
    }
}
