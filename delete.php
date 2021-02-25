<?php
    include 'includes/login.php';
    // データの受け取り
    $id = intval($_POST['id']); // idを整数として取得する
    $pass = $_POST['pass'];

    // 必須項目チェック
    if ($id == '' || $pass == ''){
        header('Location: bbs.php');
        exit();
    }

    // データベースに接続
    $dsn = 'mysql:host=localhost;dbname=tennis;charset=utf8';
    $user = 'tennisuser';
    $password = 'password'; // tennisuserに設定したパスワード

    try {
        $db = new PDO($dsn, $user, $password);
        $db->setAttribute((PDO::ATTR_EMULATE_PREPARES, false);
        // プリペアドステートメントを作成
        $stmt = $db->prepare(
            "DELETE FROM bbs WHERE id=:id AND pass=:pass" // SQLのDELETE文(DELETE FROM テーブル名 WHERE 条件)、AND条件->どちらも一致することが条件
            // ※WHERE条件の書き忘れに注意
        );
        // パラメータの割り当て
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
        // クエリの実行
        $stmt->execute();
    } catch(PDOException $e){
        echo "エラー：" . $e->getMessage();
    }
    header("Location: bbs.php");
    exit();
?>