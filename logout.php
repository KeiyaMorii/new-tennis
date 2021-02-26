<?php
    session_start();
    if (isset($_SESSION['id'])){
        unset($_SESSION['id']); // 引数に指定された変数そのものを削除する関数
        // ※unset($_SESSION);スーパーグローバル変数自体を削除してはいけない
        // $_SESSION = array(); session_destroy();を使用する
    }
    header('Location: login.php');
?>