<?php
include 'includes/login.php';
// POSTメソッドでデータの受け取り(bbs.phpから)
$name = $_POST['name'];
$title = $_POST['title'];
$body = $_POST['body'];
$pass = $_POST['pass'];

// 必須項目チェック（名前か本文が空ではないか？）
if ($name == '' || $body == ''){
    // header(Location: url) 指定したページにリダイレクト 
    header('Location: bbs.php'); // 名前か本文のどちらかが空になってたらbbs.phpへ移動
    // リダイレクトする際には、以降のコードが実行されないようにexit()を指定して処理を終了する
    exit(); // 終了
}

// 必須項目チェック（パスワードは４桁の数字か？）
// preg_matchは正規表現を用いて文字列が指定された形式と合っているかをチェックする関数、(/開始終了マーク/,^先頭,[0-9]0から9の数字,{4}4桁,$末尾)
if (!preg_match("/^[0-9]{4}$/", $pass)){
    header('Location: bbs.php');
    exit();
}
// 名前をクッキーにセット
setcookie('name', $name, time() + 60 * 60 * 24 * 30); // setcookie(クッキーの名前、保存する値、有効期限のタイムスタンプ)

// データベースに接続
$dsn = 'mysql:host=localhost;dbname=tennis;charset=utf8'; // DSNとはどのサーバにあるどんなデータベースを使うのかを指定した文字列のこと
$user = 'tennisuser';
$password = 'password'; // tennisuserに設定したパスワード

// try-catch->例外処理のための文
try{
    $db = new PDO($dsn, $user, $password); // PDO=PHP Data Objects さまざまなデータベース(DBMS)を簡単に利用できるようにする、PHPの拡張機能
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); // setAttribute属性をセット(PDO::ATTR_EMULATE_PREPARES, SQLインジェクションの危険があるためfalseに指定する)
    // プリペアドステートメントを作成
    $stmt = $db->prepare("INSERT INTO bbs (name, title, body, date, pass) VALUES (:name, :title, :body, now(), :pass)"); // PDOインスタンスのメソッドを実行するための構文
    // INSERT文はテーブルに新しいレコードを追加するSQLの構文(bbsテーブルを追加)
    // INSERT INTO テーブル名 (カラム1, カラム2, カラム3・・・) VALUES (値1, 値2, 値3・・・) ※クォーテーション不要
    // 「:~」プレースホルダ、bindParamメソッドで後から値を埋め込む/bindParam->テンプレートのプレースホルダに変数を埋め込むメソッド
    // now() MySQLの持つ現在日時を表わす関数

    // パラメータを割り当て
    $stmt->bindParam(':name', $name, PDO::PARAM_STR); // PDO::PARAM_STR - 文字列データ型を定義
    $stmt->bindParam(':title', $title, PDO::PARAM_STR);
    $stmt->bindParam(':body', $body, PDO::PARAM_STR);
    $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
    // クエリの実行、プリペアドステートメントでクエリを組み立てて、executeで実行するというのが基本的な流れ
    $stmt->execute();

    // bbs.phpに戻る
    header('Location: bbs.php');
    exit();
} catch(PDOException $e) { // PDOExceptionという種類の例外が発生したときのみcatchの中の処理が実行される
    die ('エラー：' . $e->getMessage()); // メッセージを出力して終了/exitと言語仕様的に違いはない
}
?>