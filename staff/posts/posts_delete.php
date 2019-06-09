<?php
session_start();
require('../dbconnect.php');

if(isset($_SESSION['id'])){
  $id=$_REQUEST['id'];

  $messages=$db->prepare('SELECT * FROM posts WHERE id=?');
  $messages->execute(array($id));
  $message=$messages->fetch();

  $del=$db->prepare('DELETE FROM posts WHERE id=?');
  $del->execute(array($id));
}else{
  header('Location: ../index.php');
  exit();
}

if(!$_REQUEST['id']){
  header('Location: ../index.php');
  exit();
}else{
  header('Location: posts_delete_done.php');
  exit();
}


?>
