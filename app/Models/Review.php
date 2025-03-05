<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    public function product()
    {
        //belongsToで商品(productsテーブル)との関係性を追加(多数のレビューに対して商品は1つな為)
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        //ユーザー一人に対して、複数のレビューが紐づく
        return $this->belongsTo(User::class);
    }
}
