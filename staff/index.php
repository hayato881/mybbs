<?php
session_start();
require('dbconnect.php');

if(isset($_SESSION['id'])&&$_SESSION['time']+3600>time()){
	$_SESSION['time']=time();

	$staffs=$db->prepare('SELECT * FROM staffs WHERE id=?');
	$staffs->execute(array($_SESSION['id']));
	$staff=$staffs->fetch();
}

if(!$staff['staffkey']){
  header('Location:staff_login.php');
  exit();
}


?>

<!DOCTYPE html>
<head>
  <meta charset="utf-8">
  <title>管理画面</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
<div id="wrap">
	<div id="head">
		<h1>管理画面</h1>
	</div>
	<div id="content">
		<p>スタッフID:<?php echo (htmlspecialchars($staff['id'],ENT_QUOTES)); ; ?></p>
		<p>名前:<?php echo (htmlspecialchars($staff['name'],ENT_QUOTES)); ; ?></p>
		<div style="text-align: right"><a href="members/members_list.php">メンバーを参照する</a></div>
		<div style="text-align: right"><a href="posts/posts_list.php">コメントを参照する</a></div>
		<div style="text-align: right"><a href="staff_logout.php">ログアウト</a></div>
	</div>
</div>
</body>
</html>
