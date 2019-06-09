<?php
session_start();
require('../../dbconnect.php');

if(isset($_SESSION['id'])&&$_SESSION['time']+3600>time()){
  $_SESSION['time']=time();

  $members=$db->prepare('SELECT * FROM members WHERE id=?');
  $members->execute(array($_SESSION['id']));
  $member=$members->fetch();
}

unlink('../../member_picture/'.$member['picture']);

$respone=$db->prepare('DELETE FROM posts WHERE member_id=?');
$respone->execute(array($_SESSION['id']));

$respone=$db->prepare('DELETE FROM members WHERE id=?');
$respone->execute(array($_SESSION['id']));

if(!isset($_SESSION['id'])){
  header ('Location:../../index.php');
  exit();
}else{
header('Location: member_delete_done.php');
exit();
}
