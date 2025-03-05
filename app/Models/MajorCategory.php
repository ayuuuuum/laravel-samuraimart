<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MajorCategory extends Model
{
    use HasFactory;

    public function categories()
    {
        //親カテゴリーは複数カテゴリーに紐づけられる
        return $this->hasmany(Category::class);
    }
}
