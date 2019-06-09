<?php
session_start();
require('../dbconnect.php');

if(isset($_SESSION['id'])&&$_SESSION['time']+3600>time()){
	$_SESSION['time']=time();

	$staffs=$db->prepare('SELECT * FROM staffs WHERE id=?');
	$staffs->execute(array($_SESSION['id']));
	$staff=$staffs->fetch();
}

if(!$staffs){
  header('Location:../index.php');
  exit();
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

?>

<!DOCTYPE html>
<head>
  <meta charset="utf-8">
  <title>コメント参照</title>
  <link rel="stylesheet" href="../style.css" />
</head>

<body>
	<div id="wrap">
		<div id="head">
			<h1>コメント参照</h1>
		</div>
		<div id="content">
			<div style="text-align: right"><a href="../index.php">戻る</a></div>
			<?php foreach($posts as $post): ?>
		    <div class="msg">
		      <p><?php echo(htmlspecialchars($post['message'],ENT_QUOTES)); ?><span class="name">（<?php echo(htmlspecialchars($post['name'],ENT_QUOTES)); ?>）</span>
		      <p class="day"><a href="posts_view.php?id=<?php echo(htmlspecialchars($post['id']));?>"><?php echo(htmlspecialchars($post['created'],ENT_QUOTES)); ?></a>
		      </p>

		      <?php if($post['reply_message_id']>0): ?>
		        <a href="posts_view.php?id=<?php echo(htmlspecialchars($post['reply_message_id']));?>">
		        返信元のメッセージ</a>
		      <?php endif; ?>
		        [<a href="posts_delete.php?id=<?php echo(htmlspecialchars($post['id']));?>"
		        style="color: #F33;">削除</a>]
		      </p>
	  		<?php endforeach; ?>
		  </div>
			<ul class="paging">
				<?php if($page>1): ?>
					<li><a href="posts_list.php?page=<?php echo($page-1);?>">前のページへ</a></li>
				<?php endif; ?>
				<?php if($page<$maxPage): ?>
					<li><a href="posts_list.php?page=<?php echo($page+1);?>">次のページへ</a></li>
				<?php endif; ?>
			</ul>
	</div>
</div>
</body>
</html>
