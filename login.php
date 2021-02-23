<?PHP
    session_start(); // セッション開始

    if (isset($_SESSION['id'])){
        // セッションにユーザIDがある=ログインしている
        // トップページに遷移する
        header('Location: index.php');
    } else if (isset($_POST['name']) && isset($_POST['password'])){
        // ログインしていないがユーザ名とパスワードが送信されたとき
        // データベースに接続
        $dsn = 'mysql:host=localhost;dbname=tennis;charset=utf8';
        $user = 'tennisuser';
        $password = 'password'; // tennisuserに設定したパスワード

        try {
            $db = new PDO($dsn, $user, $password);
            $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            // プリペアドステートメントを作成
            $stmt = $db->prepare("SELECT * FROM users WHERE name=:name AND password=:pass");

            // パラメータの割り当て
            $stmt->bindParam(':name', $_POST['name'], PDO::PARAM_STR);
            $stmt->bindParam(':pass', shal($_POST['password']), PDO::PARAM_STR);

            // クエリの実行
            $stmt->execute();

            if ($row = $stmt->fetch()){
                // ユーザが存在していたので、セッションにユーザIDをセット
            }
        }
    }
?>