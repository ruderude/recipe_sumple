<?php
require_once('../functions.php');
require_once('../image_config.php');

ini_set('display_errors', TRUE);
error_reporting(E_ALL);

// error_reporting(E_ALL & ~E_NOTICE);

$id = h($_GET['id'], ENT_QUOTES, 'UTF-8');
$user_name = $_POST['user_name'];
$recipe_name = $_POST['recipe_name'];
$howto = $_POST['howto'];
$category = (int) $_POST['category'];
$difficulty = (int) $_POST['difficulty'];
$budget = (int) $_POST['budget'];


echo $id;

//データベース接続
require_once("../db.php");
$dbh = db_connect();



/* アップロードがあったとき */
if (isset($_FILES['upfile']['error']) && is_int($_FILES['upfile']['error'])) {

    // バッファリングを開始
    ob_start();

    try {

        // $_FILES['upfile']['error'] の値を確認
        switch ($_FILES['upfile']['error']) {
            case UPLOAD_ERR_OK: // OK
                break;
            case UPLOAD_ERR_NO_FILE:   // ファイル未選択
                throw new RuntimeException('ファイルが選択されていません', 400);
            case UPLOAD_ERR_INI_SIZE:  // php.ini定義の最大サイズ超過
            case UPLOAD_ERR_FORM_SIZE: // フォーム定義の最大サイズ超過
                throw new RuntimeException('ファイルサイズが大きすぎます', 400);
            default:
                throw new RuntimeException('その他のエラーが発生しました', 500);
        }

        // $_FILES['upfile']['mime']の値はブラウザ側で偽装可能なので
        // MIMEタイプを自前でチェックする
        if (!$info = @getimagesize($_FILES['upfile']['tmp_name'])) {
            throw new RuntimeException('有効な画像ファイルを指定してください', 400);
        }
        if (!in_array($info[2], [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG], true)) {
            throw new RuntimeException('未対応の画像形式です', 400);
        }

        // サムネイルをバッファに出力
        $create = str_replace('/', 'createfrom', $info['mime']);
        $output = str_replace('/', '', $info['mime']);
        if ($info[0] >= $info[1]) {
            $dst_w = 400;
            $dst_h = ceil(400 * $info[1] / max($info[0], 1));
        } else {
            $dst_w = ceil(400 * $info[0] / max($info[1], 1));
            $dst_h = 400;
        }
        if (!$src = @$create($_FILES['upfile']['tmp_name'])) {
            throw new RuntimeException('画像リソースの生成に失敗しました', 500);
        }
        $dst = imagecreatetruecolor($dst_w, $dst_h);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $dst_w, $dst_h, $info[0], $info[1]);
        $output($dst);
        imagedestroy($src);
        imagedestroy($dst);

        // UPDATE処理
        $stmt = $dbh->prepare('update recipes set recipe_name = ?, category = ?, difficulty = ?, budget = ?, howto = ?, user_name = ?, image_name = ?, type = ?, raw_data = ?, thumb_data = ? where id = ?');
            $stmt->bindValue(1, $recipe_name, PDO::PARAM_STR);
            $stmt->bindValue(2, $category, PDO::PARAM_INT);
            $stmt->bindValue(3, $difficulty, PDO::PARAM_INT);
            $stmt->bindValue(4, $budget, PDO::PARAM_INT);
            $stmt->bindValue(5, $howto, PDO::PARAM_STR);
            $stmt->bindValue(6, $user_name, PDO::PARAM_STR);
            $stmt->bindValue(7, $_FILES['upfile']['name']);
            $stmt->bindValue(8, $info[2]);
            $stmt->bindValue(9, file_get_contents($_FILES['upfile']['tmp_name']));
            $stmt->bindValue(10, ob_get_clean());
            $stmt->bindValue(11, $id, PDO::PARAM_INT);
            $stmt->execute();

        $msgs[] = ['green', 'ファイルは正常にアップロードされました'];

    } catch (RuntimeException $e) {

        while (ob_get_level()) {
            ob_end_clean(); // バッファをクリア
        }
        http_response_code($e instanceof PDOException ? 500 : $e->getCode());
        $msgs[] = ['red', $e->getMessage()];

    }
}
  $dbh = null;

  echo "レシピの編集が完了しました。<br>";
?>

<!DOCTYPE>
<html lang="ja">
<head>
<title>RUDEお料理レシピの鉄人：編集</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="../teststyle.css" type="text/css" rel="stylesheet" media="screen">
<link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css"
 integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
</head>
<body>
	<div class="container center-block">
	<div class="box15 m60-b m60-t">
	<h1>RUDEお料理レシピの鉄人</h1>
	</div>
<br>
<button class="btn btn-primary"><a href="../index.php" style="color:#fff;">トップページに戻る</a></button>
	</div>
</body>
</html>
