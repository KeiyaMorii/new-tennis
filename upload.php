<?php
include 'includes/login.php';
$msg = null; // アップロード状況を表わすメッセージ

// アップロード処理
if (isset($_FILES['image']) && is_uploaded_file
($_FILES['image']['tmp_name'])){
    $old_name = $_FILES['image']['tmp_name'];

    $new_name = date("YmdHis"); // ベースとなるファイル名は日付
    $new_name .= mt_rand(); // ランダムな数字も追加
    switch (exif_imagetype($_FILES['image']['tmp_name'])){
        case IMAGETYPE_JPEG:
            $new_name .= '.jpg';
            break;
        case IMAGETYPE_GIF:
            $new_name .= '.gif';
            break;
        case IMAGETYPE_PNG:
            $new_name .= '.png';
            break;
        default:
        header('Location: upload.php');
        exit();
    }
    if (move_uploaded_file($old_name, 'album/' . $new_name)){
        $msg = 'アップロードしました。';
    } else {
        $msg = 'アップロードできませんでした。';
    }
}
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF=8">
    <title>交流サイト：画像アップロード</title>
</head>
<body>
    <h1>交流サイト：画像アップロード</h1>
    <p><a href="index.php">トップページに戻る</a></p>
    <?php
    if ($msg){
        echo '<p>' . $msg . '</p>';
    }
    ?>
    <form action="upload.php" method="post" enctype="multipart/form-data">
    <input type="file" name="image">
    <input type="submit" value="アップロード">
    </form>
</body>
</html>