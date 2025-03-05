<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\MajorCategory;
use App\Models\Product;

class WebController extends Controller
{
    public function index()
    {
        //categoriesテーブルの全てのデータを取得して$categoriesに代入
        $categories = Category::all();

        // major_categories テーブルの 全てのデータを取得 して、$major_categories に代入 
        $major_categories = MajorCategory::all();

        //商品の登録日時（created_at）でソート（降順）して、新しい順に4つ取得
        $recently_products = Product::orderBy('created_at', 'desc')->take(4)->get();

        //おすすめフラグがON(true)の商品を3つ取得
        $recommend_products = Product::where('recommend_flag', true)->take(3)->get();

        /*注目商品（各商品の平均評価を算出し、その平均評価が高い順に並べ替えている）
        平均を算出したいscoreカラムはproductsテーブルではなく、そのリレーション先のreviewsテーブルに存在⇒withAvg()メソッドを使用（第1引数がテーブル名、第2引数がカラム名）
        算出した平均値は"テーブル名_avg_カラム名"というプロパティ名で参照⇒商品の平均評価が高い順に並べ替えられる*/
        $featured_products = Product::withAvg('reviews', 'score')->orderBy('reviews_avg_score', 'desc')->take(4)->get();

        return view('web.index', compact('major_categories', 'categories', 'recommend_products', 'recently_products', 'featured_products'));
    }
}
//return view('web.index', compact('major_categories', 'categories', ' recently_products', 'recommend_products', 'featured_products'));