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
	if(!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/",$_POST['email'])&&$_POST['email']==!''){
		$error['email']='wrong';
	}
	if(strlen($_POST['password'])<4&&$_POST['password']==!''){
		$error['password']='length';
	}

	$fileName=$_FILES['image']['name'];
	if(!empty($fileName)){
		$ext =substr($fileName,-3);
		if($ext!='jpg'&&$ext!='gif'&&$ext!='png'){
			$error['image']='type';
		}
	}

	if(empty($error)){
		$member=$db->prepare('SELECT COUNT(*) AS cnt FROM members WHERE email=?');
		$member->execute(array($_POST['email']));
		$record=$member->fetch();
		if($record['cnt']>0){
			$error['email']='duplicate';
		}
	}

	if(empty($error)){
		$image=date('YmdHis').$fileName;
		move_uploaded_file($_FILES['image']['tmp_name'],'../../member_picture/'.$image);
		$_SESSION['join']=$_POST;
		$_SESSION['join']['image']=$image;
		header('Location: check.php');
		exit();
  }
}

if($_REQUEST['action']=='rewrite'&&isset($_SESSION['join'])){
	$_POST=$_SESSION['join'];
}

?>

<!DOCTYPE html>
<head>
<meta charset="UTF-8">
<title>各種設定・退会</title>
<link rel="stylesheet" href="../../style.css" />
</head>
<body>
    <div id="wrap">
      <div id="head">
        <h1>各種変更・退会</h1>
      </div>
      <div id="content">
        <form action="" method="post" enctype="multipart/form-data">
        	<dl>
            <div style="text-align: right"><a href="../index.php">マイページ</a></div>
            <p>現在のニックネーム：<?php echo  (htmlspecialchars($member['name'],ENT_QUOTES));?></p>
        		<dt>・ニックネーム</dt>
          		<dd>
                	<input type="text" name="name" size="35" maxlength="255" value="<?php echo(htmlspecialchars($_POST['name'],ENT_QUOTES));?>" />
          		</dd>
            <p>現在のメールアドレス:<?php echo  (htmlspecialchars($member['email'],ENT_QUOTES));?></p>
        		<dt>・メールアドレス</dt>
          		<dd>
                	<input type="text" name="email" size="35" maxlength="255" value="<?php echo(htmlspecialchars($_POST['email'],ENT_QUOTES));?>" />
        						<?php if ($error['email']==='wrong'): ?>
        							<p class='error'>*メールアドレスを正しく入力してください</p>
        						<?php endif; ?>
        						<?php if ($error['email']==='duplicate'): ?>
        							<p class='error'>*指定されたメールアドレスは、既に登録されています</p>
        						<?php endif; ?>
              </dd>
          	<dt>・新たなパスワード(4文字以上で入力してください)</dt>
          		<dd>
                	<input type="password" name="password" size="10" maxlength="20" value="<?php echo(htmlspecialchars($_POST['password'],ENT_QUOTES));?>" />
        						<?php if ($error['password']==='length'): ?>
        							<p class='error'>*パスワードは4文字以上で入力してください</p>
        						<?php endif; ?>
              </dd>
            <?php if(!is_numeric($member['picture'])): ?>
              <p>現在の写真<img src="../../member_picture/<?php echo(htmlspecialchars($member['picture'],ENT_QUOTES)); ?>" width="48" height="48" alt="name" /></p>
            <?php endif; ?>
        		<dt>・写真など</dt>
        		  <dd>
                	<input type="file" name="image" size="35" value="test"  />
        					<?php if ($error['image']==='type'): ?>
        						<p class='error'>*写真などは「.gif」または「.jpg」「.png」の画像を指定してください</p>
        					<?php endif; ?>
              </dd>
        	</dl>
        	<div><input type="submit" value="入力内容を確認する" /></div>
        </form>

        <div style="text-align: right"><a href="member_delete.php">退会する</a></div>
      </div>
    </div>
</body>
</html>
