<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\MajorCategory;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    //引数にRequest $requestを追加することにより、渡された値をindexアクション内で使用できるようになる
    public function index(Request $request)
    {
        //受け取った検索ワードを変数に代入
        $keyword = $request->keyword;

        $sorts = [
            '新着順' => 'created_at desc',
            '価格が安い順' => 'price asc',
            '評価が高い順' => 'reviews_avg_score desc',
        ];

        $sort_query = [];
        $sorted = "created_at desc";

        if ($request->has('select_sort')) {
            $slices = explode(' ', $request->input('select_sort'));
            $sort_query[$slices[0]] = $slices[1];
            $sorted = $request->input('select_sort');
        }

        //レビューの平均評価を取得
        $query = Product::withAvg('reviews', 'score');

        //値を受け取った場合
        if ($request->category !== null) {

            //受け取った絞り込みたいカテゴリーのIDを持つ商品データを取得&ページネーションで表示&ソート機能追加
            $products = Product::where('category_id', $request->category)->sortable($sort_query)->orderBy('created_at', 'desc')->paginate(12);
            //当該カテゴリーの商品数を表示
            $total_count = Product::where('category_id', $request->category)->count();
            //カテゴリー名を取得
            $category = Category::find($request->category);
            //親カテゴリーidを取得
            $major_category = MajorCategory::find($category->major_category_id);
        
        //検索ワードを受け取った場合
        } elseif ($keyword !== null) {
            $products = Product::where('name', 'like', "%{$keyword}%")->sortable($sort_query)->orderBy('created_at', 'desc')->paginate(12);
            $total_count = $products->total();
            $category = null;
            $major_category = null;


        } else {
            //Productモデルのデータを15件ずつ、ページネーションで表示、ソート機能追加
            $products = Product::sortable($sort_query)->orderBy('created_at', 'desc')->paginate(12);
            $total_count = $products->total();
            $category = null;
            $major_category = null;
        }

        // ソートの適用
        if (isset($sort_query['created_at'])) {
            $query->orderBy('created_at', $sort_query['created_at']);
        } elseif (isset($sort_query['price'])) {
            $query->orderBy('price', $sort_query['price']);
        } elseif (isset($sort_query['reviews_avg_score'])) {
            $query->orderBy('reviews_avg_score', 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $products = $query->paginate(12);

        //全てのカテゴリーを取得
        $categories = Category::all();
        //親カテゴリーをすべて取得
        $major_categories = MajorCategory::all();


        //第一引数には表示させるビューのファイルを指定、第二引数にはコントローラからビューに渡す変数を指定
        return view('products.index', compact('products', 'category', 'major_category', 'categories', 'major_categories',  'total_count', 'keyword', 'sorts', 'sorted'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //$categoriesにすべてのカテゴリを保存しビューへ渡す
        $categories = Category::all();
 
        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    //storeアクションは、データを受け取り、新しいデータを保存するアクション
    public function store(Request $request)
    {
        //Productモデルの変数を$product = new Product();にて作成
        $product = new Product();
        //フォームから送信されたデータが格納されている$request変数の中から、nameとdescriptionなどの項目のデータをそれぞれのカラムに保存
        $product->name = $request->input('name');
        $product->description = $request->input('description');
        $product->price = $request->input('price');
        $product->category_id = $request->input('category_id');
        //データベースへと保存
        $product->save();

        //データが保存された後はリダイレクト
        //to_route()関数は別のページに移動するために使用され、view()関数はビューファイルを表示するために使われる
        return to_route('products.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    //showアクションでは指定された商品の情報を表示
    public function show(Product $product)
    {
        //商品についての全てのレビューを取得 ページネーションで表示するよう設定（5つずつ）
        $reviews = $product->reviews()->paginate(5);

        // 平均評価を取得（Eager Loading を使用）
        $product->loadAvg('reviews', 'score');
 
        //取得したレビューをcompact関数でビューへと渡している
        return view('products.show', compact('product', 'reviews'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    //editアクションでは、編集する商品のデータをビューへと渡す役割
    public function edit(Product $product)
    {
        $categories = Category::all();
 
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //$request内に格納されているフォームに入力した更新後のデータをそれぞれのカラムに渡して上書き
        $product->name = $request->input('name');
        $product->description = $request->input('description');
        $product->price = $request->input('price');
        $product->category_id = $request->input('category_id');
        //データベースに保存（更新）
        $product->update();
        
        return to_route('products.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    //destroyアクション内には、データベース内の指定のデータを削除する処理と、削除後のリダイレクト処理を実装
    public function destroy(Product $product)
    {
        //データベースから指定の商品のデータを削除
        $product->delete();
 
        return to_route('products.index');
    }
}
