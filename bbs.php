<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF=8">
        <title>掲示板</title>
    </head>
    <body>
        <h1>掲示板</h1>
        <p><a href="index.php">トップページに戻る</a></p>
        <form action="write.php" method="post"> <!-- write.phpにPOSTメソッドを使って送信している -->
            <p>名前：<input type="text" name="name"></p>
            <p>タイトル：<input type="text" name="title"></p>
            <textarea name="body"></textarea>
            <p>削除パスワード（数字４桁）：<input type="text" name="pass"></p>
            <p><input type="submit" value="書き込む"></p>
        </form>
    </body>
</html>