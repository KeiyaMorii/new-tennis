<?php
// 1ページに表示されるコメント数
$num = 10; // 1ページに10件ずつ表示

// データベースに接続
$dsn = 'mysql:host=localhost;dbname=tennis;charset=utf8';
$user = 'tennisuser';
$passwprd = 'password'; // tennisuserに設定したパスワード

// ページ数が指定されているとき
$page = 0; // ページ数のための変数を0にリセットしておく
if (isset($_GET['page']) && $_GET['page'] > 0){
    $page = intval($_GET['page']) -1; // 指定ページ-1の値をページ数変数に代入する(intval — 変数の整数としての値を取得する)
}

try {
    $db = new PDO($dsn, $user, $password);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    // プリペアドステートメントを作成
    $stmt = $db->prepare(
        "SELECT * FORM bbs ORDER BY date DESC LIMIT :page, :num" // SELECT->データを取得する、*->全部、FROM bbs->bbsテーブルを取得する
        // ORDER BY date DESC->表を並べ替える文、ASC(昇順、日付が古い順),DESC(降順、日付が新しい順)、LIMIT :page, :num->取得件数を制限する文
    );
    // パラメータを割り当て
    $page = $page * $num;
    $stmt->bindParam(':page', $page, PDO::PARAM_INT); // PDO::PARAM_INT->INTEGER データ型
    $stmt->bindParam(':num', $num, PDO::PARAM_INT);
    // クエリの実行
    $stmt->execute(); // 取得してくるレコード郡=結果セット
} catch(PDOException $e){
    echo "エラー：" . $e->getMessage();
}
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF=8">
    <title>掲示板</title>
</head>
<body>
    <h1>掲示板</h1>
    <p><a href="index.php">トップページに戻る</a></p>
    <form action="write.php" method="post"> <!-- write.phpにPOSTメソッドを使って送信している -->
        <p>名前：<input type="text" name="name" value="<?php echo isset($_COOKIE['name'])? $_COOKIE['name'] : " " ?>"></p> <!-- Noticeエラーが表示されないように三項演算子を使う -->
        <p>タイトル：<input type="text" name="title"></p>
        <textarea name="body"></textarea>
        <p>削除パスワード（数字４桁）：<input type="text" name="pass"></p>
        <p><input type="submit" value="書き込む"></p>
    </form>
    <hr /> <!-- ?? -->
<?php
    while ($row = $stmt->fetch()): // fetchメソッドで結果セットから1レコードを取得して$rowに連想配列として代入
        $title = $row['title'] ? $row['title'] : '（無題）'; // 三項演算子(条件式 ? 式1 : 式2),条件式の結果がTRUEなら式1をFALSEなら式2を$titleに代入
?>
    <p>名前：<?php echo $row['name'] ?></p>
    <p>タイトル：<?php echo $title ?></p>
    <p><?php echo nl2br($row['body'], false) ?></p> <!-- 本文、改行コードを改行タグに変えて表示 -->
    <p><?php echo $row['date'] ?></p>
    <form action="delete.php" method="post">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
        削除パスワード：<input type="password" name="pass">
        <input type="submit" value="削除">
    </form>
<?php
    endwhile;

    // ページ数の表示
    try {
        // プリペアドステートメント作成
        $stmt = $db->prepare("SELECT COUNT(*) FROM bbs"); // SELECT COUNT(*)->カウントしたいカラム名で、値がNULLでないカラムの数をカウントできる(*)で取得した行数がわかる
        // クエリの実行
        $stmt->execute();
    } catch (PDOException $e){
        echo "エラー：" . $e->getMessage();
    }

    // コメントの件数を取得
    $comments = $stmt->fetchColumn(); // fetchColumnメソッドで最初のカラムの内容を取得、引数には何番目のカラムかを入力する
    // ページ数を計算
    $max_page = ceil($comments / $num); // ceil(数値)->端数の切り上げ
    echo '<p>';
    for ($i = 1; $i <= $max_page; $i++){
        echo '<a href="bbs.php?page=' . $i . '">' . $i . '</a>&nbsp;';
    }
    echo '</p>';
?>
</body>
</html>