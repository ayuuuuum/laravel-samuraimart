<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Product extends Model
{
    use HasFactory, Sortable;

    //$fillableにカラムを指定することで、Product::createで商品を登録できるようになる
    protected $fillable = [
        'name',
        'description',
        'price',
        'category_id',
        'image',
        'recommend_flag',
        'carriage_flag',
    ];

    public function category()
    {
        //紐づける先のモデル名をメソッドの引数にすることで紐づけできる
        //複数ある商品を一つのカテゴリで参照する
        return $this->belongsTo(Category::class);
    }

    public function reviews()
    {
        //hasManyで一対多の関係性をモデルに追加(商品一つに対して、いくつものレビューが紐づくため)
        return $this->hasMany(Review::class);
    }

    public function favorited_users() {

        //withTimestamps()メソッドをつなげることで、中間テーブルの場合もcreated_atカラムやupdated_atカラムの値が自動的に更新されるようになる
        //多対多
        return $this->belongsToMany(User::class)->withTimestamps();
    }
}
