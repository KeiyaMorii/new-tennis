<?php
$new_width = 250; // サムネイルの幅

// 元画像の縦横サイズを取得、配列キーは0と1
list($width, $height) = getimagesize($_FILES['file']
['tmp_name']);

// 画像のサイズ比率を計算
$rate = $new_width / $width; // 比率
$new_height = $rate * $height; // サムネイルの高さ

// 計算したサイズでキャンバスを作成する
$canvas = imagecreatetruecolor($new_width, $new_height);

// アップロードした画像の拡張子によって新ファイル名と画像の読み込み方を変える
switch (exif_imagetype($_FILES['file']['tmp_name'])){
    // JPEG
    case IMAGETYPE_JPEG:
        $image = imagecreatefromjpeg($_FILES['file']['tmp_name']); // 元画像のリソースを取得
        // 画像の縮小を行う
        imagecopyresampled($canvas, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height); // 画像のサイズを変えるときに再サンプリングを行い、きれいにサイズ変更を行う
        imagejpeg($canvas, 'images/new image.jpeg'); // 第１引数に保存したい画像リソース、第２引数に保存先のパスを指定
        break;

    // GIF
    case IMAGETYPE_GIF:
        $image = imagecreatefromgif($_FILES['file']['tmp_name']);
        imagecopyresampled($canvas, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        imagegif($canvas, 'images/new image.gif');
        break;

    // PNG
    case IMAGETYPE_PNG:
        $image = imagecreatefrompng($_FILES['file']['tmp_name']);
        imagecopyresampled($canvas, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        imagepng($canvas, 'images/new image.png');
        break;
        
    // 画像以外のファイルのとき
    default:
    exit();
}
imagedestroy($image);
imagedestroy($canvas);
?>