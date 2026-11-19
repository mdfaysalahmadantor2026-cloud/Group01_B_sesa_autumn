<?php require '../includes/helpers.php';$u=need();
$r=q('SELECT user_id,name,email,university,department,role FROM users WHERE user_id=?',[$u])->fetch();
if(!$r){$_SESSION=[];out(false,'Login required',null,401);}
$r['unread']=(int)q('SELECT COUNT(*) FROM notifications WHERE user_id=? AND is_read=0',[$u])->fetchColumn();
out(true,'',$r);
