<?php

namespace App\Admin\Controllers;

use App\Models\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class UserController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'User';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    //ユーザーの一覧表示
    protected function grid()
    {
        //Grid を作成し、User モデルのデータを一覧表示できるようにする
        $grid = new Grid(new User());

        // データベースの各カラムを管理画面に表示する
        $grid->column('id', __('Id'))->sortable();
        $grid->column('name', __('Name'));
        $grid->column('email', __('Email'));
        $grid->column('email_verified_at', __('Email verified at'));
        $grid->column('postal_code', __('Postal code'));
        $grid->column('address', __('Address'));
        $grid->column('phone', __('Phone'));
        $grid->column('created_at', __('Created_at'))->sortable();
        $grid->column('updated_at', __('Updated at'))->sortable();
        $grid->column('deleted_at', __('Deleted at'))->sortable();

        //検索フィルター like 検索（部分一致検索）ができるようにする
        $grid->filter(function($filter) {
            $filter->like('name', 'ユーザー名');
            $filter->like('email', 'メールアドレス');
            $filter->like('postal_code', '郵便番号');
            $filter->like('address', '住所');
            $filter->like('phone', '電話番号');
            //created_at の日付範囲で検索できるようにする
            $filter->between('created_at', '登録日')->datetime();
            //ソフトデリート されたユーザーだけをフィルターして表示できるようにする
            $filter->scope('trashed', 'Soft deleted data')->onlyTrashed();
        });

        //作成した Grid を返して、管理画面で一覧表示できるようにする
        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    //detail($id) は、特定のユーザーの詳細ページを作成するためのメソッド
    protected function detail($id)
    {
        //User::findOrFail($id) で、指定された id のユーザーを取得
        $show = new Show(User::findOrFail($id));

        //field('カラム名', __('表示名'))→ データベースのカラムを表示する設定
        //__('Id') のように __('〇〇') を使っているのは 多言語対応（翻訳可能） にするため
        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('email', __('Email'));
        $show->field('email_verified_at', __('Email verified at'));
        $show->field('postal_code', __('Postal code'));
        $show->field('address', __('Address'));
        $show->field('phone', __('Phone'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('deleted_at', __('Deleted at'));

        //Show インスタンスを返して、管理画面でユーザー詳細を表示できるようにする
        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    //form() は、新しいユーザーの作成または既存ユーザーの編集フォームを作成するメソッド
    protected function form()
    {
        //new Form(new User()) で User モデルに対応したフォームを作成
        $form = new Form(new User());

        $form->text('name', __('Name'));
        $form->email('email', __('Email'));
        //日時選択フィールド（デフォルト値は現在日時）
        $form->datetime('email_verified_at', __('Email verified at'))->default(date('Y-m-d H:i:s'));
        $form->password('password', __('Password'));
        $form->text('postal_code', __('Postal code'));
        $form->textarea('address', __('Address'));
        $form->mobile('phone', __('Phone'));
        $form->datetime('deleted_at', __('Deleted at'))->default(NULL);

        //saving() メソッドは、フォームの保存処理が実行される直前に実行される Laravel-Admin のイベント処理
        //パスワードのハッシュ化処理を行っている
        $form->saving(function (Form $form) {
            //もし form に入力された password があるかつ、既存のパスワードと違う場合
            if ($form->password && $form->model()->password != $form->password) {
                //bcrypt() でハッシュ化して保存する
                $form->password = bcrypt($form->password);
            } else {
                //もし password フィールドが空の場合 既存のパスワードをそのまま維持
                $form->password = $form->model()->password;
            }
        });

        //Form インスタンスを返して、管理画面で ユーザーの作成・編集 ができるようにする
        return $form;
    }
}
