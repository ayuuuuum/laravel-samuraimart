<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            //$table->softDeletes()とすると、deleted_atというカラムが追加される⇒データは消さずに deleted_at に削除日時が入る
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //users テーブルから ソフトデリート機能（deleted_at カラム）を削除 
            $table->dropSoftDeletes();
        });
    }
};
