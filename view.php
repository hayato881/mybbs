<?php
session_start();
require('dbconnect.php');

if(empty($_REQUEST['id'])){
	header('Location:index.php');
	exit();
}

if(isset($_SESSION['id'])&&$_SESSION['time']+3600>time()){
	$_SESSION['time']=time();

	$members=$db->prepare('SELECT * FROM members WHERE id=?');
	$members->execute(array($_SESSION['id']));
	$member=$members->fetch();
}

$posts=$db->prepare('SELECT m.name,m.picture,p.* FROM members m,posts p WHERE m.id=p.member_id AND p.id=?');
$posts->execute(array($_REQUEST['id']));
?>

<!DOCTYPE html>
<head>
	<meta charset="UTF-8">
	<title>簡易掲示板</title>
	<link rel="stylesheet" href="style.css" />
</head>

<body>
<div id="wrap">
  <div id="head">
    <h1>簡易掲示板</h1>
  </div>
  <div id="content">
  <div style="text-align: right">&laquo;<a href="index.php">トップページにもどる</a></div>
		<?php if($member): ?>
	<div style="text-align: right">&laquo;<a href="mypage/index.php">マイページにもどる</a></div>
		<?php endif; ?>



	<?php if($post=$posts->fetch()):?>
    <div class="msg">
			<?php if(!is_numeric($post['picture'])): ?>
				<img src="member_picture/<?php echo (htmlspecialchars($post['picture']));?>" width="48" height="48" />
			<?php else: ?>
				<img src="images/no_image.png" width="48" height="48" alt="name" />
			<?php endif; ?>
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
