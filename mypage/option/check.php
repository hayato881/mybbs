<?php
session_start();
require('../../dbconnect.php');

if(!isset($_SESSION['id'])){
  header ('Location:../../index.php');
  exit();
}

if(isset($_SESSION['id'])&&$_SESSION['time']+3600>time()){
  $_SESSION['time']=time();

  $members=$db->prepare('SELECT * FROM members WHERE id=?');
  $members->execute(array($_SESSION['id']));
  $member=$members->fetch();
}

if(!empty($_POST)){

  if($_SESSION['join']['name']!==''){
  	$statemnt=$db->prepare('UPDATE members SET name=? WHERE id=?');
  	  $statemnt->execute(array(
  		$_SESSION['join']['name'],
  		$_SESSION['id']
  	));
  }

  if($_SESSION['join']['email']!==''){
  	$statemnt=$db->prepare('UPDATE members SET email=? WHERE id=?');
  	  $statemnt->execute(array(
  		$_SESSION['join']['email'],
  		$_SESSION['id']
  	));
  }

  if($_SESSION['join']['password']!==''){
  	$statemnt=$db->prepare('UPDATE members SET password=? WHERE id=?');
  	  $statemnt->execute(array(
  		md5($_SESSION['join']['password']),
  		$_SESSION['id']
  	));
  }

  if($_SESSION['join']['image']!==''&&!is_numeric($_SESSION['join']['image'])){
    unlink('../../member_picture/'.$member['picture']);
  	$statemnt=$db->prepare('UPDATE members SET picture=? WHERE id=?');
  	echo $statemnt->execute(array(
  		$_SESSION['join']['image'],
  		$_SESSION['id']
  	));
  }

  header('Location: check_done.php');
  exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="../../style.css" />
<title>各種変更</title>
</head>
<body>
  <div id="wrap">
    <div id="head">
      <h1>各種変更</h1>
    </div>
    </>
    <div id="content">
      <p>記入した内容を確認して、「変更する」ボタンをクリックしてください</p>
      <form action="" method="post">
      	<input type="hidden" name="action" value="submit" />
      	<dl>
      		<dt>・ニックネーム</dt>
      			  <dd><?php if($_SESSION['join']['name']==''): ?>
                    <p>変更なし</p>
                  <?php else: ?>
      				      <?php echo(htmlspecialchars($_SESSION['join']['name'],ENT_QUOTES)); ?>
                  <?php endif; ?>
              </dd>
      		<dt>・メールアドレス</dt>
      			  <dd><?php if($_SESSION['join']['email']==''): ?>
                    <p>変更なし</p>
                  <?php else:?>
      				      <?php echo(htmlspecialchars($_SESSION['join']['email'],ENT_QUOTES)); ?>
                  <?php endif;?>
              </dd>
      		<dt>・パスワード</dt>
      			  <dd><?php if($_SESSION['join']['password']==''): ?>
                    <p>変更なし</p>
                  <?php else:?>
      				      【表示されません】
                  <?php endif;?>
      			  </dd>
      		<!-- <dt>・写真など</dt> -->
      		<dt>
      				<?php if(!is_numeric($_SESSION['join']['image'])): ?>
      					<img src="../../member_picture/<?php echo(htmlspecialchars($_SESSION['join']['image'],ENT_QUOTES)); ?>" width="48" height="48" >
      				<?php endif; ?>
      		</dt>
      	</dl>
      	<div><a href="index.php?action=rewrite">&laquo;&nbsp;書き直す</a> | <input type="submit" value="変更する" /></div>
      </form>
    </div>
  </div>
</body>
</html>
