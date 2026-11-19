<?php require '../includes/helpers.php';$me=need();$d=inp();
if($_SERVER['REQUEST_METHOD']==='POST'){$a=$d['action']??'';
if($a==='read')q('UPDATE notifications SET is_read=1 WHERE notification_id=? AND user_id=?',[(int)($d['id']??0),$me]);
elseif($a==='read_all')q('UPDATE notifications SET is_read=1 WHERE user_id=?',[$me]);
elseif($a==='clear')q('DELETE FROM notifications WHERE user_id=?',[$me]);
else out(false,'Unknown action',null,400);out(true,'Done');}
out(true,'',q('SELECT notification_id,message,type,is_read,sent_date FROM notifications WHERE user_id=? ORDER BY sent_date DESC,notification_id DESC',[$me])->fetchAll());
