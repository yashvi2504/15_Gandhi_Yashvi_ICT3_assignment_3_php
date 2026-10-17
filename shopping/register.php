<?php
require __DIR__.'/config.php'; $error='';
if(empty($_SESSION['captcha'])) $_SESSION['captcha']=(string)random_int(1000,9999);
if($_SERVER['REQUEST_METHOD']==='POST'){
  if(!hash_equals($_SESSION['captcha']??'',trim($_POST['captcha']??''))) $error='CAPTCHA is incorrect.';
  else {
    $name=trim($_POST['name']??'');$email=filter_var($_POST['email']??'',FILTER_VALIDATE_EMAIL);$pw=$_POST['password']??'';
    if($name===''||!$email||strlen($pw)<6)$error='Enter valid details. Password must be at least 6 characters.';
    else {try{$s=$pdo->prepare('INSERT INTO users(name,email,password_hash) VALUES(?,?,?)');$s->execute([$name,$email,password_hash($pw,PASSWORD_DEFAULT)]);unset($_SESSION['captcha']);header('Location: login.php?registered=1');exit;}catch(PDOException $e){$error='Email may already be registered.';}}
  }
  $_SESSION['captcha']=(string)random_int(1000,9999);
}
?><!doctype html><html><body><h1>Register</h1><p style="color:red"><?= h($error) ?></p>
<form method="post"><p>Name <input name="name" required></p><p>Email <input type="email" name="email" required></p><p>Password <input type="password" name="password" minlength="6" required></p>
<p>CAPTCHA: <strong><?= h($_SESSION['captcha']) ?></strong> <button type="button" onclick="location.reload()">Refresh</button></p><p>Enter CAPTCHA <input name="captcha" required></p><button>Register</button></form></body></html>
