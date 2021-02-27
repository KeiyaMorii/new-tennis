<?PHP
    session_start(); // セッション開始(セッションを使った処理をする場合には最初に宣言する必要がある)

    if (isset($_SESSION['id'])){
        // スーパーグローバル変数で参照
        // セッションにユーザIDがある=ログインしている
        // トップページに遷移する
        header('Location: index.php'); // ログインしていればindex.phpへ遷移する
    } else if (isset($_POST['name']) && isset($_POST['password'])){
        // ログインしていないがユーザ名とパスワードが送信されたとき
        // データベースに接続し、ユーザが存在するか確認
        $dsn = 'mysql:host=localhost;dbname=tennis;charset=utf8';
        $user = 'tennisuser';
        $password = 'password'; // tennisuserに設定したパスワード

        try {
            $db = new PDO($dsn, $user, $password);
            $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            // プリペアドステートメントを作成
            // 名前が:nameかつ、パスワードが:passであるユーザを検索
            $stmt = $db->prepare("SELECT * FROM users WHERE name=:name AND password=:pass");

            // パラメータの割り当て
            $stmt->bindParam(':name', $_POST['name'], PDO::PARAM_STR);
            // データベースに登録されているのは暗号化した後のパスワードなので、検索を行うときも暗号化したパスワードとマッチするかどうかを考える
            $stmt->bindParam(':pass', sha1($_POST['password']), PDO::PARAM_STR);

            // クエリの実行
            $stmt->execute();

            if ($row = $stmt->fetch()){
                // ユーザが存在していたので、セッションにユーザIDをセット
                $_SESSION['id'] = $row['id'];
                // セッションIDの再作成
                session_regenerate_id(true);
                header('Location: index.php'); // index.phpへ遷移
                exit();
            } else {
                // 1レコードも取得できなかったとき
                // ユーザ名・パスワードが間違っている可能性あり
                // もう一度ログインフォームを表示
                header('Location: login.php');
                exit();
            }
        } catch(PDOException $e) {
            die ('エラー：' . $e->getMessage());
        }

    } else {
        // ログインしていない場合はログインフォームを表示する
    ?>

    <html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8"> <!-- 文書の文字コードを指定 -->
        <title>テニスサークル交流サイト</title>
    </head>
    <body>
        <h1>テニスサークル交流サイト</h1>
        
        <h2>ログイン</h2>
        <form action="login.php" method="post">
        <p>ユーザ名：<input type="text" name="name"></p>
        <p>パスワード：<input type="password" name="password"></p>
        <p><input type="submit" value="ログイン"></p>
        </form>
    </body>
    </html>
    <?php } ?>