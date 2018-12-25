<?php
require_once('../functions.php');
require_once('../image_config.php');

session_start();

header("Content-type: text/html; charset=utf-8");

// ログイン状態のチェック
if (!isset($_SESSION["account"])) {
	header("Location: ../login_form.php");
	exit();
}

$account = $_SESSION['account'];
echo "<p>".h($account,ENT_QUOTES)."さん、こんにちは！</p>";

echo "<a href='/login/logout.php' class='btn31'>ログアウトする</a><br>";

try{
  if(empty($_GET['id'])) throw new Exception('ID不正');
  $id = (int) $_GET['id'];

  //データベース接続
  require_once("../db.php");
  $dbh = db_connect();

    $sql = "delete from recipes where id = ?";
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $id, PDO::PARAM_INT);
    $stmt->execute();

    $dbh = null;
    echo "ID: " . h($id, ENT_QUOTES, 'UTF-8') . "の削除が完了しました。";
} catch(Exception $e){
  echo "エラー発生： " . h($e->getMessage(), ENT_QUOTES, 'UTF-8') . "<br>";
  die();
}

?>

<!DOCTYPE>
<html lang="ja">
<head>
<title>RUDEお料理レシピの鉄人：削除</title>
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
