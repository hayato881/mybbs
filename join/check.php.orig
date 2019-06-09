<?php
session_start();
require('../dbconnect.php');

if(!isset($_SESSION['join'])){
	header('Location: index.php');
	exit();
}

if(!empty($_POST)){
	$statemnt=$db->prepare('INSERT INTO members SET name=?,email=?,password=?,picture=?,created=NOW()');
		$statemnt->execute(array(
		$_SESSION['join']['name'],
		$_SESSION['join']['email'],
		md5($_SESSION['join']['password']),
		$_SESSION['join']['image']
	));
	unset($_SESSION['join']);

	header('Location: check_done.php');
	exit();
}


?>
<!DOCTYPE html>
<head>
	<meta charset="UTF-8">
	<title>会員登録</title>
	<link rel="stylesheet" href="../style.css" />
</head>
<body>
<div id="wrap">
<div id="head">
<h1>会員登録</h1>
</div>

<div id="content">
<p>記入した内容を確認して、「登録する」ボタンをクリックしてください</p>
<form action="" method="post">
	<input type="hidden" name="action" value="submit" />
	<dl>
		<dt>・ニックネーム</dt>
			<dd>
				<?php echo(htmlspecialchars($_SESSION['join']['name'],ENT_QUOTES)); ?>
      </dd>
		<dt>・メールアドレス</dt>
			<dd>
				<?php echo(htmlspecialchars($_SESSION['join']['email'],ENT_QUOTES)); ?>
      </dd>
		<dt>・パスワード</dt>
			<dd>
				【表示されません】
			</dd>
		<!-- <dt>・写真など</dt> -->
			<dd>
				<?php if(!is_numeric($_SESSION['join']['image'])): ?>
					<img src="../member_picture/<?php echo(htmlspecialchars($_SESSION['join']['image'],ENT_QUOTES)); ?>" width="48" height="48" >
				<?php endif; ?>
			</dd>
	</dl>
	<div><a href="index.php?action=rewrite">&laquo;&nbsp;書き直す</a> | <input type="submit" value="登録する" /></div>
</form>
</div>

</div>
</body>
</html>
