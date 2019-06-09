<?php
session_start();
require('../dbconnect.php');

if(!isset($_SESSION['id'])){
  header ('Location:../index.php');
}
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
    <p>削除しました</p>
    <p>&laquo;<a href="../index.php">トップページにもどる</a></p>
	  <p>&laquo;<a href="posts_list.php">コメント一覧にもどる</a></p>
  </div>
</div>
</body>
</html>
