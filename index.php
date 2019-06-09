<?php
session_start();
require('dbconnect.php');

if(isset($_SESSION['id'])&&$_SESSION['time']+3600>time()){
	$_SESSION['time']=time();

	$members=$db->prepare('SELECT * FROM members WHERE id=?');
	$members->execute(array($_SESSION['id']));
	$member=$members->fetch();
}

if(!empty($_POST)){
	if($_POST['message']!==''){
		$message=$db->prepare('INSERT INTO posts SET member_id=?,message=?,reply_message_id=?,created=NOW()');
		$message->execute(array(
			$member['id'],
			$_POST['message'],
			$_POST['reply_post_id']
		));

		header('Location:index.php');
		exit();
	}
}

$page=$_REQUEST['page'];
if($page==''){
	$page=1;
}
$page=max($page,1);

$counts=$db->query('SELECT COUNT(*) AS cnt FROM posts');
$cnt=$counts->fetch();
$maxPage=ceil($cnt['cnt']/5);
$page=min($page,$maxPage);

$start=($page-1)*5;

$posts=$db->prepare('SELECT m.name,m.picture,p.* FROM members m,posts p WHERE m.id=p.member_id ORDER BY p.created DESC LIMIT ?,5');
$posts->bindparam(1,$start,PDO::PARAM_INT);
$posts->execute();

if(isset($_REQUEST['res'])){
$respone=$db->prepare('SELECT m.name,m.picture,p.* FROM members m,posts p WHERE m.id=p.member_id AND p.id=?');
$respone->execute(array($_REQUEST['res']));

$table=$respone->fetch();
$message='@'.$table['name'].''.$table['message'];
}

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
		<?php if($member): ?>
			<div style="text-align: right"><a href="mypage/index.php">マイページ</a></div>
			<div style="text-align: right"><a href="logout.php">ログアウト</a></div>
		<?php else: ?>
			<div style="text-align: right"><a href="login.php">ログイン・会員登録</a></div>
		<?php endif; ?>
		<?php foreach($posts as $post): ?>
		  <div class="msg">
				<?php if(!is_numeric($post['picture'])): ?>
					<img src="member_picture/<?php echo(htmlspecialchars($post['picture'],ENT_QUOTES)); ?>" width="48" height="48" alt="name" />
				<?php else: ?>
					<img src="images/no_image.png" width="48" height="48" alt="name" />
				<?php endif; ?>
			  <p><?php echo(htmlspecialchars($post['message'],ENT_QUOTES)); ?><span class="name">（<?php echo(htmlspecialchars($post['name'],ENT_QUOTES)); ?>）</span>
					<?php if($member): ?>
						[<a href="index.php?res=<?php echo(htmlspecialchars($post['id'],ENT_QUOTES)); ?>">返信</a>]
					<?php endif; ?>
				</p>

				<p class="day"><a href="view.php?id=<?php echo(htmlspecialchars($post['id']));?>"><?php echo(htmlspecialchars($post['created'],ENT_QUOTES)); ?></a>
				</p>

				<?php if($post['reply_message_id']>0): ?>
					<a href="view.php?id=<?php echo(htmlspecialchars($post['reply_message_id']));?>">
					返信元のメッセージ</a>
				<?php endif; ?>

				<?php if ($_SESSION['id']==$post['member_id']): ?>
					[<a href="delete.php?id=<?php echo(htmlspecialchars($post['id']));?>"
					style="color: #F33;">削除</a>]
				<?php endif; ?>
				</p>
			</div>
		<?php endforeach; ?>

		<ul class="paging">
			<?php if($page>1): ?>
				<li><a href="index.php?page=<?php echo($page-1);?>">前のページへ</a></li>
			<?php endif; ?>
			<?php if($page<$maxPage): ?>
				<li><a href="index.php?page=<?php echo($page+1);?>">次のページへ</a></li>
			<?php endif; ?>
		</ul>

		<?php if($member): ?>
			<form action="" method="post">
	      <dl>
	        <dt><?php echo (htmlspecialchars($member['name'],ENT_QUOTES)); ?>さん、メッセージをどうぞ</dt>
	        <dd>
	          <textarea name="message" cols="50" rows="5" align="left"><?php echo(htmlspecialchars($message,ENT_QUOTES));?></textarea>
	          <input type="hidden" name="reply_post_id" value="<?php echo(htmlspecialchars($_REQUEST['res'],ENT_QUOTES));?>" />
	        </dd>
	      </dl>
	      <div>
	        <p>
	          <input type="submit" value="投稿する" />
	        </p>
	      </div>
    	</form>
		<?php endif; ?>

  </div>
</div>
</body>
</html>
