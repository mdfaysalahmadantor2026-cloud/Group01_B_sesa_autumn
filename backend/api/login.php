<?php require '../includes/helpers.php';post();$d=inp();
$u=q('SELECT * FROM users WHERE email=?',[strtolower(s($d['email']??''))])->fetch();
if(!$u||!password_verify((string)($d['password']??''),$u['password_hash']))out(false,'Invalid email or password',null,401);
session_regenerate_id(true);$_SESSION['uid']=(int)$u['user_id'];$_SESSION['role']=$u['role'];
out(true,'Welcome back!',['role'=>$u['role']]);
