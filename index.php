<?php
require_once('functions.php');
require_once('image_config.php');

session_start();

// ini_set('display_errors', TRUE);
// error_reporting(E_ALL);

header("Content-type: text/html; charset=utf-8");

// ログイン状態のチェック
if (!isset($_SESSION["account"])) {
	header("Location: /login/login_form.php");
	exit();
}

$account = $_SESSION['account'];
echo "<p>".h($account,ENT_QUOTES)."さん、こんにちは！</p>";

echo "<a href='/login/logout.php' class='btn31'>ログアウトする</a>";



session_start();

if (!function_exists('imagecreatetruecolor')) {
    echo 'GD not installed';
    exit;
}
?>

<!DOCTYPE>
<html lang="ja">
<head>
<title>RUDEお料理レシピの鉄人</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="teststyle.css" type="text/css" rel="stylesheet" media="screen">
<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
</head>
<body>
	<div class="container center-block">
	<div class="box15 m60-b m60-t">
	<h1>RUDEお料理レシピの鉄人</h1>
	</div>
	<div>
		<a href="create/create.php" class="btn32" id="newbtn">レシピの新規登録</a>
	</div>
	<br>
<?php

//データベース接続
require_once("db.php");
$dbh = db_connect();

    $sql = "select * FROM recipes ORDER BY id DESC;";
    $stmt = $dbh->query($sql);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $dbh = null;

?>



<?php foreach($result as $row): ?>
<div class="row">
  <div class="col-xs-6 col-md-4 recipeName">調理名</div>
  <div class="col-xs-12 col-md-8 recipeName2"><?php echo h(strip_tags($row['recipe_name'])) ?></div>
</div>
<div class="row">
  <div class="col-xs-9 col-md-6">
		<ul class="text-left list-group">
			<li class="list-group-item">
		<?=sprintf(
					'<a href="review.php?id=%d"><img src="data:%s;base64,%s" alt="%s" width="400" /></a>',
					$row['id'],
					image_type_to_mime_type($row['type']),
					base64_encode($row['thumb_data']),
					h($row['image_name'])
			)?>
		</li>
		</ul>
	</div>
  <div class="col-xs-9 col-md-6">
		<ul class="text-left list-group">
				<li class="list-group-item">投稿者：<?php echo h(strip_tags($row['user_name'])) ?></li>
				<li class="list-group-item">予算：<?php echo h(strip_tags($row['budget'])) ?>円くらい</li>
				<li class="list-group-item">難易度：<?php switch ( h(strip_tags($row['difficulty'])) ){
					case 1:
					echo '簡単';
					break;
					case 2:
					echo '普通';
					break;
					case 3:
					echo '難しい';
					break;
					}?></li>
				<li class="list-group-item"><?php echo "<a href=review.php?id=" . h($row['id'], ENT_QUOTES, 'UTF-8') . "><i class='far fa-hand-point-right'></i>見る!</a>" ?></li>
				<li class="list-group-item"><?php echo "<a href=edit/edit.php?id=" . h($row['id'], ENT_QUOTES, 'UTF-8') . "><i class='fas fa-pencil-alt'></i>編集!</a>" ?></li>
				<li class="list-group-item"><?php echo "<a href=delete/delete.php?id=" . h($row['id'], ENT_QUOTES, 'UTF-8') . "><i class='fas fa-eraser'></i>消す!</a>" ?></li>
				<li class="list-group-item"><i class="far fa-clock"></i><?=h($row['date'])?><br clear="all" /></li>
		</ul>
	</div>
</div>
<br>
<?php endforeach; ?>
<br>
<?php if (isset($success)) : ?>
<div class="msg success"><?php echo h($success); ?></div>
<?php endif; ?>
<?php if (isset($error)) : ?>
<div class="msg error"><?php echo h($error); ?></div>
<?php endif; ?>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script>
$(function() {
  $('.msg').fadeOut(3000);
  $('#my_file').on('change', function() {
                   $('#my_form').submit();
                   });
  });
</script>
</body>
</html>
