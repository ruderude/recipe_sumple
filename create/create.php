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

?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <title>入力フォーム</title>
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
	<h1>入力フォーム</h1>
	</div>
	<br>
	  <form class="form-horizontal" method="post" action="add.php" enctype="multipart/form-data" accept="image/*" capture="camera">

			<div class="form-group">
				<label for="InputText">投稿者</label>
				<input type="text" class="form-control" id="InputText" placeholder="投稿者" name="user_name" value="">
			</div>
			<div class="form-group">
				<label for="InputText">料理名</label>
				<input type="text" class="form-control" id="InputText" placeholder="料理名" name="recipe_name" value="">
			</div>
			<div class="form-group">
				<label for="InputSelect">カテゴリー</label>
				<select name="category" class="form-control" id="InputSelect">
		      <option value="">洗濯してください</option>
		      <option value="1">和食</option>
		      <option value="2">中華</option>
		      <option value="3">洋食</option>
		    </select>
			</div>
			<div class="form-group">
			<label for="optionsRadios">難易度</label>
			<div class="radio">
				<label>
					<input type="radio" name="difficulty" id="optionsRadios" value="1"> 簡単
				</label>
			</div>
			<div class="radio">
				<label>
					<input type="radio" name="difficulty" id="optionsRadios" value="2"> 普通
				</label>
			</div>
			<div class="radio">
			<label>
				<input type="radio" name="difficulty" id="optionsRadios" value="3"> 難しい
			</label>
			</div>
			</div>
			<div class="form-group">
				<label for="InputNumber">予算</label>
				<input type="number" class="form-control" id="InputNumber" placeholder="予算" name="budget" value="">円くらい
			</div>
			<div class="form-group">
				<label for="InputTextarea">作り方</label>
				<textarea class="form-control" id="InputTextarea" placeholder="作り方を入力してください。" name="howto" cols="50" rows="5" maxlength="5000"></textarea>
			</div>
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
