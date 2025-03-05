<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use function PHPUnit\Framework\returnSelf;

class Category extends Model
{
    use HasFactory;

    public function products()
    {
        //カテゴリを取り扱うCategoryモデルから、そのカテゴリを持つ商品を取得できるように紐づける
        //1つのカテゴリーには複数の商品が紐づく
        return $this->hasMany(Product::class);
    }

    public function major_category()
    {
        //カテゴリーは1つの親カテゴリーに属する
        return $this->belongsTo(MajorCategory::class);
    }
}
