<?php
//Laravel Admin にCSVインポート機能を追加するためのカスタムツール

namespace App\Admin\Extensions\Tools;

use Encore\Admin\Admin;
use Encore\Admin\Grid\Tools\AbstractTool;

class CsvImport extends AbstractTool
{
    //JavaScriptのコードを返すメソッド(このスクリプトは、管理画面の「CSVインポート」ボタンに関連付けられる)
   protected function script()
   {
        //<<< SCRIPT はヒアドキュメントで、複数行の文字列を扱うための書き方
       return <<< SCRIPT
       
       //.csv-import クラスのボタンをクリックすると、<input type="file" id="files"> をクリックする（ファイル選択ダイアログを開く）
       $('.csv-import').click(function() {
           var select = document.getElementById('files');
           document.getElementById("files").click();
           //ユーザーがCSVファイルを選択したら、フォームデータ (FormData) を作成してAjaxで送信 する
           select.addEventListener('change',function() {
               var formdata = new FormData();
               formdata.append( "file", $("input[name='product']").prop("files")[0] );
               //Laravelは CSRF対策 をしているため、X-CSRF-TOKEN をリクエストヘッダーに追加
               $.ajaxSetup({
                   headers: {
                       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                   }
               });
               //選択したCSVファイルを products/import にPOST送信
               $.ajax({
                   type : "POST",
                   url : "products/import",
                   data : formdata,
                   processData : false,
                   contentType : false,
                   success: function (response) {
                        //成功したら ページをリロード ($.pjax.reload()) してデータを反映
                       $.pjax.reload("#pjax-container");
                       //toastr.success() で 「CSVのアップロードが成功しました」と通知を表示
                       toastr.success('CSVのアップロードが成功しました');
                   }
               });
           });
       });

       SCRIPT;
   }

   public function render()
   {
        // Laravel Admin にスクリプトを登録
       Admin::script($this->script());
       //csv_upload.blade.php というビューを返す
       return view('csv_upload');
   }
}