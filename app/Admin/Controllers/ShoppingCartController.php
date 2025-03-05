<?php

namespace App\Admin\Controllers;

use App\Models\ShoppingCart;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ShoppingCartController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'ShoppingCart';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    //grid() は管理画面でデータ一覧（テーブル）を表示する設定をするメソッド
    protected function grid()
    {
        //Grid クラスのインスタンスを作成  ShoppingCartモデルを使ってショッピングカートのデータを一覧表示する
        $grid = new Grid(new ShoppingCart());

        //identifier（カートの識別ID）と instance（ユーザーID）を ソート可能（並び替え可能）なカラム に設定
        $grid->column('identifier', __('ID'))->sortable();
        $grid->column('instance', __('User ID'))->sortable();
        //カラムにtotalRow()を付与すると、合計を表示できるようになる
        //フィルターと組み合わせると、表示しているデータの合計も計算可能
        $grid->column('price_total', __('Price total'))->totalRow(); //→ 商品の合計金額
        $grid->column('qty', __('Qty'))->totalRow(); //→ 商品の合計数量
        $grid->column('created_at', __('Created at'))->sortable();
        $grid->column('updated_at', __('Updated at'))->sortable();

        //フィルターを設定して、データの検索をしやすくする
        $grid->filter(function($filter) {
            //$filter->disableIdFilter()とすることでデフォルトのIDフィルターを無効化
            $filter->disableIdFilter();
            //identifier（カートID）で検索可能
            $filter->equal('identifier', 'ID');
            //instance（ユーザーID）で検索可能
            $filter->equal('instance', 'User ID');
            //created_at の範囲で検索可能（期間指定）
            $filter->between('created_at', '登録日')->datetime();
        });

        //「新規作成」ボタンを無効化(カートのデータは管理者が手動で追加するものではないので、新規作成を無効にしている)
        $grid->disableCreateButton();
        $grid->actions(function ($actions) {
            //表示・編集・削除は不要なので、actions()を使って操作できないようにしている
            $actions->disableDelete();
            $actions->disableEdit();
            $actions->disableView();
        });


        //設定した Grid を返すことで、管理画面に適用される
        return $grid;
    }

}
