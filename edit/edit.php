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

echo "<a href='/login/logout.php' class='btn31'>ログアウトする</a>";


try {
  if(empty($_GET['id'])) throw new Exception('ID不正');
  $id = (int) $_GET['id'];


  //データベース接続
  require_once("../db.php");
  $dbh = db_connect();

    $sql = "select * from recipes where id = ?";
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $dbh = null;

} catch(Exception $e){
  echo "エラー発生： " . h($e->getMessage(), ENT_QUOTES, 'UTF-8') . "<br>";
  die();
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<title>編集フォーム</title>
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
	<h1>レシピの編集</h1>
	</div>
<br>
  <form class="form-horizontal" method="post" action="<?php echo 'update.php?id=' . h($id, ENT_QUOTES, 'UTF-8') ?>" enctype="multipart/form-data" accept="image/*" capture="camera">

		<div class="form-group">
			<label for="InputText">投稿者</label>
			<input type="text" class="form-control" id="InputText" placeholder="投稿者" name="user_name" value="<?php echo h($result['user_name'], ENT_QUOTES, 'UTF-8'); ?>">
		</div>
		<div class="form-group">
			<label for="InputText">料理名</label>
			<input type="text" class="form-control" id="InputText" placeholder="料理名" name="recipe_name" value="<?php echo h($result['recipe_name'], ENT_QUOTES, 'UTF-8'); ?>">
		</div>
		<div class="form-group">
			<label for="InputSelect">カテゴリー</label>
			<select name="category" class="form-control" id="InputSelect">
	      <option value="">洗濯してください</option>
	      <option value="1" <?php if($result['category'] === 1) echo "selected" ?>>和食</option>
	      <option value="2" <?php if($result['category'] === 2) echo "selected" ?>>中華</option>
	      <option value="3" <?php if($result['category'] === 3) echo "selected" ?>>洋食</option>
	    </select>
		</div>
		<div class="form-group">
		<label for="optionsRadios">難易度</label>
		<div class="radio">
			<label>
				<input type="radio" name="difficulty" id="optionsRadios" value="1" <?php if($result['difficulty'] === 1) echo "checked" ?>> 簡単
			</label>
		</div>
		<div class="radio">
			<label>
				<input type="radio" name="difficulty" id="optionsRadios" value="2" <?php if($result['difficulty'] === 2) echo "checked" ?>> 普通
			</label>
		</div>
		<div class="radio">
		<label>
			<input type="radio" name="difficulty" id="optionsRadios" value="3" <?php if($result['difficulty'] === 3) echo "checked" ?>> 難しい
		</label>
		</div>
		</div>
		<div class="form-group">
			<label for="InputNumber">予算</label>
			<input type="number" class="form-control" id="InputNumber" placeholder="予算" name="budget" value="<?php echo h($result['budget'], ENT_QUOTES, 'UTF-8'); ?>">円くらい
		</div>
		<div class="form-group">
			<label for="InputTextarea">作り方</label>
			<textarea class="form-control" id="InputTextarea" placeholder="作り方を入力してください。" name="howto" cols="50" rows="5" maxlength="5000"><?php echo h($result['howto'], ENT_QUOTES, 'UTF-8'); ?></textarea>
		</div>
    <input type="hidden" name="id" value="<?php echo $id; ?>">
    <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo h(MAX_FILE_SIZE); ?>">
		<div class="form-group">
			<label for="InputFile">画像ファイル</label>
			<input type="file" name="upfile" value="" id="InputFile">
			<input type="submit" class="btn btn-primary" value="送信">
			<p class="help-block" style="color:tomato;">画像ファイルをアップしないと投稿できません。イメージタイプは、Jpeg、Gif、Png、の形式が使えます。</p>
		</div>
  </form>
	<button class="btn btn-primary"><a href="../index.php" style="color:#fff;">トップページに戻る</a></button>
	</div>
	<br>
</body>
</html>
