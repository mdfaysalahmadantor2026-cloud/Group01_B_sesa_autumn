<?php require '../includes/helpers.php';$me=need();$d=inp();
if($_SERVER['REQUEST_METHOD']==='POST'){
$r=q("SELECT * FROM requests WHERE request_id=? AND status='accepted' AND (sender_id=? OR receiver_id=?)",[(int)($d['request_id']??0),$me,$me])->fetch();
if(!$r)out(false,'Accepted request not found',null,404);
$dt=$d['date']??'';$tm=$d['time']??'';$m=$d['mode']??'';
if(!preg_match('/^\d{4}-\d\d-\d\d$/',$dt)||!strtotime($dt)||!preg_match('/^\d\d:\d\d/',$tm)||!in_array($m,['Online','In-person']))out(false,'Valid date, time and mode are required',null,422);
if($dt<date('Y-m-d'))out(false,'Date cannot be in the past',null,422);
q('INSERT INTO sessions(request_id,session_date,session_time,mode) VALUES(?,?,?,?)',[$r['request_id'],$dt,$tm,$m]);
$p=$r['sender_id']==$me?$r['receiver_id']:$r['sender_id'];$n=q('SELECT name FROM users WHERE user_id=?',[$me])->fetchColumn();
notify($p,"$n scheduled a session on $dt at $tm ($m)",'session');out(true,'Session scheduled',null,201);}
$rows=q("SELECT s.session_id,s.request_id,s.session_date,TIME_FORMAT(s.session_time,'%H:%i') session_time,s.mode,sk.skill_name,IF(r.sender_id=?,ru.name,su.name) partner,IF(r.sender_id=?,r.receiver_id,r.sender_id) partner_id,s.session_date<=CURDATE() past,(SELECT COUNT(*) FROM reviews v WHERE v.session_id=s.session_id AND v.reviewer_id=?) reviewed FROM sessions s JOIN requests r ON r.request_id=s.request_id JOIN skills sk ON sk.skill_id=r.skill_id JOIN users su ON su.user_id=r.sender_id JOIN users ru ON ru.user_id=r.receiver_id WHERE r.sender_id=? OR r.receiver_id=? ORDER BY s.session_date DESC",[$me,$me,$me,$me,$me])->fetchAll();
$open=q("SELECT r.request_id,sk.skill_name,IF(r.sender_id=?,ru.name,su.name) partner,IF(r.sender_id=?,r.receiver_id,r.sender_id) partner_id FROM requests r JOIN skills sk ON sk.skill_id=r.skill_id JOIN users su ON su.user_id=r.sender_id JOIN users ru ON ru.user_id=r.receiver_id WHERE r.status='accepted' AND (r.sender_id=? OR r.receiver_id=?) AND NOT EXISTS(SELECT 1 FROM sessions s WHERE s.request_id=r.request_id)",[$me,$me,$me,$me])->fetchAll();
out(true,'',['sessions'=>$rows,'unscheduled'=>$open]);
