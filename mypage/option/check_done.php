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

if(!isset($_SESSION['id'])){
  header ('Location:index.php');
  exit();
}
?>

<!DOCTYPE html>
<head>
	<meta charset="UTF-8">
	<title>簡易掲示板</title>
	<link rel="stylesheet" href="../../style.css" />
</head>

<body>
<div id="wrap">
  <div id="head">
    <h1>簡易掲示板</h1>
  </div>
  <div id="content">
    <p>変更しました</p>
    <p>&laquo;<a href="../../index.php">トップページにもどる</a></p>
	  <p>&laquo;<a href="../index.php">マイページにもどる</a></p>
  </div>
</div>
</body>
</html>
