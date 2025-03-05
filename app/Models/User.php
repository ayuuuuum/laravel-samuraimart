<?php

namespace App\Models;

//メール認証の有効化
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

//メール認証の有効化
class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    //論理削除カラム(deleted_at)が日付(Datetime型)であることを宣言するためのもの
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    //住所などを扱うカラムを追加したので、その情報をアカウント作成時に保存できるようにする
    protected $fillable = [
        'name',
        'email',
        'password',
        'postal_code',
        'address',
        'phone'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function reviews()
    {
        //ユーザー一人に対して、複数のレビューが紐づく
        return $this->hasMany(Review::class);
    }

    public function favorite_products() {

        //withTimestamps()メソッドをつなげることで、中間テーブルの場合もcreated_atカラムやupdated_atカラムの値が自動的に更新されるようになる
        //多対多
        return $this->belongsToMany(Product::class)->withTimestamps();
    }
}
