<?php require '../includes/helpers.php';post();$d=inp();
$n=s($d['name']??'',100);$e=strtolower(s($d['email']??'',150));$p=(string)($d['password']??'');
if(!$n||!filter_var($e,FILTER_VALIDATE_EMAIL)||strlen($p)<6)out(false,'Valid name, email and a password of 6+ characters are required',null,422);
if(q('SELECT 1 FROM users WHERE email=?',[$e])->fetch())out(false,'Email already registered',null,409);
q("INSERT INTO users(name,email,password_hash,university,department,role) VALUES(?,?,?,?,?,'student')",[$n,$e,password_hash($p,PASSWORD_DEFAULT),s($d['university']??'',150),s($d['department']??'',150)]);
out(true,'Account created. Please log in.',null,201);
