<?php
session_start();
require('dbconnect.php');

if(!empty($_POST)){
  $name=$_POST['name'];

  if($_POST['name']!==''&&$_POST['password']!==''){
    $login=$db->prepare('SELECT * FROM staffs WHERE name=? AND password=?');
    $login->execute(array(
      $_POST['name'],
      md5($_POST['password'])
    ));
    $staff=$login->fetch();

    if($staff){
      $_SESSION['id']=$staff['id'];
      $_SESSION['time']=time();

      header('Location: index.php');
      exit();
    }else{
      $error['login']='failed';
    }
  }else{
    $error['login']='blank';
  }
}
?>


<!DOCTYPE html>
<head>
  <meta charset="utf-8">
  <title></title>
  <link rel="stylesheet" href="style.css" />
</head>

<body>
  <div id="wrap">
    <div id="head">
      <h1>Login</h1>
    </div>
    <div id="content">
      <div id="lead"
        <p>名前とパスワードを記入してログインしてください。</p>
      </div>
      <form action="" method="post">
        <dl>
          <dt>・名前</dt>
          <dd>
            <input type="text" name="name" size="35" maxlength="255" value="<?php echo htmlspecialchars($name,ENT_QUOTES); ?>" />

          </dd>
          <dt>・パスワード</dt>
          <dd>
            <input type="password" name="password" size="35" maxlength="255" value="<?php echo htmlspecialchars($_POST['password'],ENT_QUOTES); ?>" />
          </dd>
          <?php if($error['login']==='blank'): ?>
            <p class="error">*名前とパスワードをご記入ください</p>
          <?php endif; ?>
          <?php if($error['login']==='failed'): ?>
            <p class="error">*ログインに失敗しました。正しくご記入ください</p>
          <?php endif; ?>

        </dl>
        <div>
          <input type="submit" value="ログインする" />
        </div>
      </form>
</body>
</html>
