<?php
session_start();
require('../dbconnect.php');

if(empty($_REQUEST['id'])){
	header('Location:../index.php');
	exit();
}

if(isset($_SESSION['id'])&&$_SESSION['time']+3600>time()){
	$_SESSION['time']=time();

	$staffs=$db->prepare('SELECT * FROM staffs WHERE id=?');
	$staffs->execute(array($_SESSION['id']));
	$staff=$staffs->fetch();
}

$posts=$db->prepare('SELECT m.name,m.picture,p.* FROM members m,posts p WHERE m.id=p.member_id AND p.id=?');
$posts->execute(array($_REQUEST['id']));
?>

<!DOCTYPE html>
<head>
	<meta charset="UTF-8">
	<title>簡易掲示板</title>
	<link rel="stylesheet" href="../style.css" />
</head>

<body>
<div id="wrap">
  <div id="head">
    <h1>簡易掲示板</h1>
  </div>
  <div id="content">
    <div style="text-align: right">&laquo;<a href="../index.php">トップページにもどる</a></div>
  	<?php if($staff): ?>
  	  <div style="text-align: right">&laquo;<a href="posts_list.php">コメント一覧にもどる</a></div>
  	<?php endif; ?>
  	<?php if($post=$posts->fetch()):?>
      <div class="msg">
  			<p><?php echo (htmlspecialchars($post['message']));?><span class="name">（<?php echo (htmlspecialchars($post['name']));?>）</span></p>
  	    <p class="day"><?php echo (htmlspecialchars($post['created']));?></p>
      </div>
  	<?php else:?>
  		<p>その投稿は削除されたか、URLが間違えています</p>
  	<?php endif;?>
  </div>
</div>
</body>
</html>
