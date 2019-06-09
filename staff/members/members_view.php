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

$members=$db->prepare('SELECT * FROM members  WHERE id=?');
$members->execute(array($_REQUEST['id']));
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
  <div id=content>
    <div id="member">
      <div style="text-align: right">&laquo;<a href="../index.php">トップページにもどる</a></div>
    	<?php if($staff): ?>
    	  <div style="text-align: right">&laquo;<a href="members_list.php">コメント一覧にもどる</a></div>
    	<?php endif; ?>
    	<?php if($member=$members->fetch()):?>
        <div class="member">
    			<p>ID:<?php echo (htmlspecialchars($member['id']));?></p>
          <p>名前:<?php echo (htmlspecialchars($member['name']));?></p>
          <div>
            <?php if(!is_numeric($member['picture'])): ?>
              <img src="../../member_picture/<?php echo(htmlspecialchars($member['picture'],ENT_QUOTES)); ?>" width="48" height="48" alt="name" />
            <?php else: ?>
              <img src="../../images/no_image.png" width="48" height="48" alt="name" />
            <?php endif; ?>
          </div>
         <p>created:<?php echo (htmlspecialchars($member['created']));?></p>
         <p>modified:<?php echo (htmlspecialchars($member['modified']));?></p>
        </div>
    	<?php else:?>
    		<p>その投稿は削除されたか、URLが間違えています</p>
    	<?php endif;?>
    </div>
  </div>
</div>
</body>
</html>
