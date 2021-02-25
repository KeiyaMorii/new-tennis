<?php
include 'includes/login.php'; // ログイン処理の呼び出し
$fp = fopen("info.txt", "r"); // info.txtを開きファイルポインタを取得
?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
  <title>テニスサークル交流サイト</title>
</head>
<body>
  <h1>テニスサークル交流サイト</h1>
  <h2>メニュー</h2>
  <p>
  <a href="album.php">アルバム</a>
  <a href="bbs.php">掲示板</a>
  <a href="logout.php">ログアウト</a>
  </p>
  <h2>お知らせ</h2>
  <?php if ($fp){
    $title = fgets($fp); // info.txtのファイルポインタを読み込み
    if ($title){
      echo '<a href="info.php">' . $title . '</a>';
    } else {
      echo 'お知らせはありません。';
    }
    fclose($fp); // info.txtを閉じる
  } else {
    echo 'お知らせはありません。';
  }
  ?>
</body>
</html>