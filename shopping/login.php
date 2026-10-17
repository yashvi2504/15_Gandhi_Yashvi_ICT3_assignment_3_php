<?php
require __DIR__.'/config.php';$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 $s=$pdo->prepare('SELECT * FROM users WHERE email=?');$s->execute([trim($_POST['email']??'')]);$u=$s->fetch();
 if($u&&password_verify($_POST['password']??'',$u['password_hash'])){session_regenerate_id(true);$_SESSION['user_id']=$u['id'];$_SESSION['user_name']=$u['name'];header('Location: index.php');exit;}
 $error='Invalid email or password.';
}
?><!doctype html><html><body><h1>User Login</h1>
<a href="logout.php">Logout</a><?php if(isset($_GET['registered']))echo '<p>Registration successful. Please log in.</p>'; ?><p style="color:red"><?= h($error) ?></p>
<form method="post"><p>Email <input type="email" name="email" required></p><p>Password <input type="password" name="password" required></p><button>Login</button></form></body></html>
