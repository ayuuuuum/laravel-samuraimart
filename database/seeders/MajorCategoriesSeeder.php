<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MajorCategory;

class MajorCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //親カテゴリーの名前を定義
        $major_category_names = [
            '本', 'コンピュータ', 'ディスプレイ'
        ];

        //各親カテゴリのデータを `major_categories` テーブルに追加
        foreach ($major_category_names as $major_category_name) {
            MajorCategory::create([
                'name' => $major_category_name,
                'description' => $major_category_name,
            ]);
        }
    }
}
