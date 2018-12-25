<?php
require_once('functions.php');
require_once('image_config.php');

session_start();

header("Content-type: text/html; charset=utf-8");

// ログイン状態のチェック
if (!isset($_SESSION["account"])) {
	header("Location: /login/login_form.php");
	exit();
}

$account = $_SESSION['account'];
echo "<p>".h($account,ENT_QUOTES)."さん、こんにちは！</p>";

echo "<a href='/login/logout.php' class='btn31'>ログアウトする</a>";

try {
  if(empty($_GET['id'])) throw new Exception('ID不正');
  $id = (int) $_GET['id'];

  //データベース接続
  require_once("db.php");
  $dbh = db_connect();

  $sql = "select * FROM recipes where id = ?";
  $stmt = $dbh->prepare($sql);
  $stmt->bindValue(1, $id, PDO::PARAM_INT);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  // print_r($result);
  $dbh = null;

} catch(Exception $e){
  echo "エラー発生： " . h($e->getMessage(), ENT_QUOTES, 'UTF-8') . "<br>";
  die();
}
// printf($result);
$dir = "/goodbtn"; //フォルダのパス
require_once $_SERVER['DOCUMENT_ROOT'] . $dir .'/GoodBtn.php';
$goodBtn = new GoodBtn($dir);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <title>料理レシピ詳細ページ</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="teststyle.css" type="text/css" rel="stylesheet" media="screen">
		<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css"
		integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
		<?php $goodBtn->head(); ?>
</head>
<body>
	<div class="container center-block">
	<div class="box15 m60-b m60-t">
	<h1>料理レシピ詳細ページ</h1>
	</div>
	<br>


<div class="row">
  <div class="col-xs-6 col-md-4 recipeName">調理名</div>
  <div class="col-xs-12 col-md-8 recipeName2"><?php echo h(strip_tags($result['recipe_name'])) ?></div>
</div>
<div class="row">
	<div class="col-xs-6 col-md-4 recipeRow">無限いいねボタン</div>
	<div class="col-xs-12 col-md-8"><?php $goodBtn->viewBtn(); ?></div>
</div>
<div class="row">
	<div class="col-xs-6 col-md-4 recipeRow">投稿者</div>
	<div class="col-xs-12 col-md-8"><?php echo h(strip_tags($result['user_name'])) ?></div>
</div>
<div class="row">
	<div class="col-xs-6 col-md-4 recipeRow">カテゴリー</div>
	<div class="col-xs-12 col-md-8">
		<?php switch(h(strip_tags($result['category']))){
												case 1:
													echo '和食';
													break;
												case 2:
													echo '中華';
													break;
												case 3:
													echo '洋食';
													break;
											}?></div>
</div>
<div class="row">
	<div class="col-xs-6 col-md-4 recipeRow">予算</div>
	<div class="col-xs-12 col-md-8"><?php echo h(strip_tags($result['budget'])) ?>円くらい</div>
</div>
<div class="row">
	<div class="col-xs-6 col-md-4 recipeRow">難易度</div>
	<div class="col-xs-12 col-md-8">
		<?php switch(h(strip_tags($result['difficulty']))){
	                      case 1:
	                        echo '簡単';
	                        break;
	                      case 2:
	                        echo '普通';
	                        break;
	                      case 3:
	                        echo '難しい';
	                        break;
	                    }?></div>
</div>
<div class="row">
	<div class="col-xs-6 col-md-4 recipeRow">作り方</div>
</div>
<div class="row">
	<div class="col-xs-12 col-md-8"><?php echo nl2br(h(strip_tags($result['howto']))) ?></div>
</div>
<br>
<div class="row">
  <div class="col-xs-12 col-md-8"><?=sprintf(
			'<a href="image_view.php?id=%d"><img src="data:%s;base64,%s" alt="%s" width="400" /></a>',
			$result['id'],
			image_type_to_mime_type($result['type']),
			base64_encode($result['thumb_data']),
			h($result['image_name'])
	)?></div>
</div>
<br>
		<button class="btn btn-primary"><a href=edit/edit.php?id=<?php echo h($result['id'], ENT_QUOTES, 'UTF-8') ?> style="color:#fff;">編集</a></button>
		<button class="btn btn-primary"><a href=delete/delete.php?id=<?php echo h($result['id'], ENT_QUOTES, 'UTF-8') ?> style="color:#fff;">削除</a></button>
		<button class="btn btn-primary"><a href="index.php" style="color:#fff;">トップページに戻る</a></button>
</div>
<br>
</body>
</html>
