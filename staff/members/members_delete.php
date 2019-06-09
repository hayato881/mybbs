<?php
session_start();
require('../dbconnect.php');

if(isset($_SESSION['id'])){
  $id=$_REQUEST['id'];

  $members=$db->prepare('SELECT * FROM members WHERE id=?');
  $members->execute(array($id));
  $member=$members->fetch();

  $del=$db->prepare('DELETE FROM members WHERE id=?');
  $del->execute(array($id));
}else{
  header('Location: ../index.php');
  exit();
}

if(!$_REQUEST['id']){
  header('Location: ../index.php');
  exit();
}else{
  header('Location: members_delete_done.php');
  exit();
}


?>
