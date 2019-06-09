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
  header('Location:../staff_login.php');
  exit();
}

$page=$_REQUEST['page'];
if($page==''){
	$page=1;
}
$page=max($page,1);

$counts=$db->query('SELECT COUNT(*) AS cnt FROM members');
$cnt=$counts->fetch();
$maxPage=ceil($cnt['cnt']/5);
$page=min($page,$maxPage);

$start=($page-1)*5;

$members=$db->prepare('SELECT * FROM members WHERE id ORDER BY id DESC LIMIT ?,5');
$members->bindparam(1,$start,PDO::PARAM_INT);
$members->execute();


?>

<!DOCTYPE html>
<head>
  <meta charset="utf-8">
  <title>メンバー参照</title>
  <link rel="stylesheet" href="../style.css" />
</head>

<body>
<div id="wrap">
	<div id="head">
		<h1>メンバー参照</h1>
	</div>
	<div id="content">
		<div style="text-align: right"><a href="../index.php">戻る</a></div>
		<div class="member">
			<?php foreach($members as $member): ?>
		      <?php if(!is_numeric($member['picture'])): ?>
		        <img src="../../member_picture/<?php echo(htmlspecialchars($member['picture'],ENT_QUOTES)); ?>" width="48" height="48" alt="name" />
		      <?php else: ?>
		        <img src="../../images/no_image.png" width="48" height="48" alt="name" />
		      <?php endif; ?>
		      <a href="members_view.php?id=<?php echo(htmlspecialchars($member['id']));?>""><span class="name">（<?php echo(htmlspecialchars($member['name'],ENT_QUOTES)); ?>）</span></a>
					[<a href="members_delete.php?id=<?php echo(htmlspecialchars($member['id']));?>"
					style="color: #F33;">削除</a>]
		  <?php endforeach; ?>
			<ul class="paging">
				<?php if($page>1): ?>
					<li><a href="members_list.php?page=<?php echo($page-1);?>">前のページへ</a></li>
				<?php endif; ?>
				<?php if($page<$maxPage): ?>
					<li><a href="members_list.php?page=<?php echo($page+1);?>">次のページへ</a></li>
				<?php endif; ?>
			</ul>
		</div>
	</div>
</div>
</body>
</html>
