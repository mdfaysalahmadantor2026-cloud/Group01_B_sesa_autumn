<?php require '../includes/helpers.php';$u=need();
$c=fn($sql)=>(int)q($sql,[$u])->fetchColumn();
$data=['counts'=>[
'pending_received'=>$c("SELECT COUNT(*) FROM requests WHERE receiver_id=? AND status='pending'"),
'pending_sent'=>$c("SELECT COUNT(*) FROM requests WHERE sender_id=? AND status='pending'"),
'upcoming'=>(int)q('SELECT COUNT(*) FROM sessions s JOIN requests r ON r.request_id=s.request_id WHERE (r.sender_id=? OR r.receiver_id=?) AND s.session_date>=CURDATE()',[$u,$u])->fetchColumn(),
'unread'=>$c('SELECT COUNT(*) FROM notifications WHERE user_id=? AND is_read=0')],
'matches'=>array_slice(matches($u),0,4),
'pending'=>q("SELECT r.request_id,u.name sender,s.skill_name,r.requested_date FROM requests r JOIN users u ON u.user_id=r.sender_id JOIN skills s ON s.skill_id=r.skill_id WHERE r.receiver_id=? AND r.status='pending' ORDER BY r.requested_date DESC LIMIT 5",[$u])->fetchAll(),
'sessions'=>q("SELECT s.session_id,s.session_date,s.session_time,s.mode,sk.skill_name,IF(r.sender_id=?,ru.name,su.name) partner FROM sessions s JOIN requests r ON r.request_id=s.request_id JOIN skills sk ON sk.skill_id=r.skill_id JOIN users su ON su.user_id=r.sender_id JOIN users ru ON ru.user_id=r.receiver_id WHERE (r.sender_id=? OR r.receiver_id=?) AND s.session_date>=CURDATE() ORDER BY s.session_date,s.session_time LIMIT 5",[$u,$u,$u])->fetchAll(),
'notifications'=>q('SELECT message,type,is_read,sent_date FROM notifications WHERE user_id=? ORDER BY sent_date DESC,notification_id DESC LIMIT 5',[$u])->fetchAll()];
out(true,'',$data);
