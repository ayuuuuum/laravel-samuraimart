<?php

namespace App\Admin\Controllers;

use App\Models\Product;
use App\Models\Category;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Admin\Extensions\Tools\CsvImport;
use Goodby\CSV\Import\Standard\Lexer;
use Goodby\CSV\Import\Standard\Interpreter;
use Goodby\CSV\Import\Standard\LexerConfig;
use Illuminate\Http\Request;

class ProductController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Product';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    //管理画面で使う 一覧表示（grid）
    protected function grid()
    {
        $grid = new Grid(new Product());

        $grid->column('id', __('Id'))->sortable();
        $grid->column('name', __('Name'));
        $grid->column('description', __('Description'));
        $grid->column('price', __('Price'))->sortable();;
        //一覧画面ではカテゴリーIDではなく、カテゴリー名を表示するようにしている 
        $grid->column('category.name', __('Category Name'));
        //CRUD画面で商品画像の設定や表示ができるようになる
        $grid->column('image', __('Image'))->image();
        //おすすめ商品
        $grid->column('recommend_flag', __('Recommend Flag'));
        //送料
        $grid->column('carriage_flag', __('Carriage Flag'));
        $grid->column('created_at', __('Created at'))->sortable();;
        $grid->column('updated_at', __('Updated at'))->sortable();;

        $grid->filter(function($filter) {
            $filter->like('name', '商品名');
            $filter->like('description', '商品説明');
            $filter->between('price', '金額');
            $filter->in('category_id', 'カテゴリー')->multipleSelect(Category::all()->pluck('name', 'id'));
            $filter->equal('recommend_flag', 'おすすめフラグ')->select(['0' => 'false', '1' => 'true']);
            $filter->equal('carriage_flag', '送料フラグ')->select(['0' => 'false', '1' => 'true']);
        });

        $grid->tools(function ($tools) {
            //CSVインポートボタンを商品管理画面に配置
            $tools->append(new CsvImport());
        });

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Product::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('description', __('Description'));
        $show->field('price', __('Price'));
        //表示画面ではカテゴリーIDではなく、カテゴリー名を表示するようにしてい
        $show->filed('category.name', __('Category Name'));
        ////CRUD画面で商品画像の設定や表示ができるようになる
        $show->filed('image', __('Image'))->image();
        //おすすめ商品
        $show->field('recommend_flag', __('Recommend Flag'));
        //送料
        $show->field('carriage_flag', __('Carriage Frag'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    //管理画面で使うフォーム（form）
    protected function form()
    {
        $form = new Form(new Product());

        $form->text('name', __('Name'));
        $form->textarea('description', __('Description'));
        $form->number('price', __('Price'));
        //存在するカテゴリー名から選択できるようにしている
        $form->select('category_id', __('Category Name'))->options(Category::all()->pluck('name', 'id'));
        $form->image('image', __('Image'));
        //おすすめ商品
        $form->switch('recommend_flag', __('Recommend Flag'));
        //送料
        $form->switch('carriage_flag', __('Carriage Flag'));

        return $form;
    }

    //csvImport()でCSVを解析して、商品データを登録
    public function csvImport(Request $request)
    {
        $file = $request->file('file');
        $lexer_config = new LexerConfig();
        $lexer = new Lexer($lexer_config);

        $interpreter = new Interpreter();
        $interpreter->unstrict();

        $rows = array();
        $interpreter->addObserver(function (array $row) use (&$rows) {
            $rows[] = $row;
        });

        $lexer->parse($file, $interpreter);
        foreach ($rows as $key => $value) {

            if (count($value) == 7) {
                Product::create([
                    'name' => $value[0],
                    'description' => $value[1],
                    'price' => $value[2],
                    'category_id' => $value[3],
                    'image' => $value[4],
                    'recommend_flag' => $value[5],
                    'carriage_flag' => $value[6],
                ]);
            }
        }

        return response()->json(
            ['data' => '成功'],
            200,
            [],
            JSON_UNESCAPED_UNICODE
        );
    }
}
