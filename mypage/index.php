<?php
session_start();
require('../dbconnect.php');

if(isset($_SESSION['id'])&&$_SESSION['time']+3600>time()){
  $_SESSION['time']=time();

  $members=$db->prepare('SELECT * FROM members WHERE id=?');
  $members->execute(array($_SESSION['id']));
  $member=$members->fetch();
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

$posts=$db->prepare('SELECT p.* FROM posts p WHERE p.member_id=? ORDER BY p.created DESC LIMIT ?,5');
$posts->execute(array($member['id']));
$posts->bindparam(1,$start,PDO::PARAM_INT);
$posts->execute();

if(!isset($_SESSION['id'])){
  header ('Location:../index.php');
}

?>


<!DOCTYPE html>
<head>
	<meta charset="UTF-8">
	<title>Mypage</title>
	<link rel="stylesheet" href="../style.css" />
</head>
<body>
  <div id="wrap">
    <div id="head">
      <h1>マイページ</h1>
    </div>
    <div id="content">
      <div style="text-align: right"><a href="../index.php">トップページ</a></div>
      <div style="text-align: right"><a href="option/index.php">各種変更・退会</a></div>
      <div style="text-align: right"><a href="../logout.php">ログアウト</a></div>

      <p>お名前:<?php echo (htmlspecialchars($member['name'],ENT_QUOTES)); ?> さん</p>
      <?php if(!is_numeric($member['picture'])): ?>
        <img src="../member_picture/<?php echo(htmlspecialchars($member['picture'],ENT_QUOTES)); ?>" width="48" height="48" alt="name" />
      <?php else: ?>
        <img src="../images/no_image.png" width="48" height="48" alt="name" />
      <?php endif; ?>
      <p>メッセージ一覧</p>
      <?php foreach($posts as $post): ?>
        <div class="msg">
          <?php if ($_SESSION['id']==$post['member_id']): ?>
            <p><?php echo(htmlspecialchars($post['message'],ENT_QUOTES)); ?></p>
            <p class="day"><a href="../view.php?id=<?php echo(htmlspecialchars($post['id']));?>"><?php echo(htmlspecialchars($post['created'],ENT_QUOTES)); ?></a></p>
          <?php endif; ?>

          <?php if($post['reply_message_id']>0): ?>
  					<a href="../view.php?id=<?php echo(htmlspecialchars($post['reply_message_id']));?>">
  					返信元のメッセージ</a>
  				<?php endif; ?>

          <?php if ($_SESSION['id']==$post['member_id']): ?>
            [<a href="../delete.php?id=<?php echo(htmlspecialchars($post['id']));?>"
            style="color: #F33;">削除</a>]
          <?php endif; ?>

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
    </div>
  </div>
</body>
</html>
