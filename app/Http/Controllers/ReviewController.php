<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //内容（content）必須入力チェック
        $request->validate([
            'title' => 'required|max:20', 
            'content' => 'required'
        ]);

        $review = new Review();
        //変数$requestから取得
        $review->title = $request->input('title');
        $review->content = $request->input('content');
        $review->product_id = $request->input('product_id');
        //レビューを投稿したユーザーIDをAuth::user()->idで取得
        $review->user_id = Auth::user()->id;
        //フォームから送信された評価をデータベースに保存
        $review->score = $request->input('score');
        $review->save();

        return back();
    }
}
