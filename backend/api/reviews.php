<?php require '../includes/helpers.php';$me=need();$d=inp();
if($_SERVER['REQUEST_METHOD']==='POST'){
$sid=(int)($d['session_id']??0);$rt=(int)($d['rating']??0);
$s=q('SELECT s.*,r.sender_id,r.receiver_id FROM sessions s JOIN requests r ON r.request_id=s.request_id WHERE s.session_id=? AND (r.sender_id=? OR r.receiver_id=?)',[$sid,$me,$me])->fetch();
if(!$s)out(false,'Session not found',null,404);
if($s['session_date']>date('Y-m-d'))out(false,'You can review after the session date',null,422);
if($rt<1||$rt>5)out(false,'Rating must be 1-5',null,422);
if(q('SELECT 1 FROM reviews WHERE session_id=? AND reviewer_id=?',[$sid,$me])->fetch())out(false,'You already reviewed this session',null,409);
q('INSERT INTO reviews(session_id,reviewer_id,rating,comment) VALUES(?,?,?,?)',[$sid,$me,$rt,s($d['comment']??'',1000)]);
$to=$s['sender_id']==$me?$s['receiver_id']:$s['sender_id'];$n=q('SELECT name FROM users WHERE user_id=?',[$me])->fetchColumn();
notify($to,"$n left you a $rt-star review",'review');out(true,'Review submitted',null,201);}
$recv=q("SELECT v.rating,v.comment,v.review_date,u.name reviewer FROM reviews v JOIN users u ON u.user_id=v.reviewer_id JOIN sessions s ON s.session_id=v.session_id JOIN requests r ON r.request_id=s.request_id WHERE v.reviewer_id<>? AND (r.sender_id=? OR r.receiver_id=?) ORDER BY v.review_date DESC",[$me,$me,$me])->fetchAll();
$avg=q('SELECT avg_rating,review_count FROM user_ratings WHERE user_id=?',[$me])->fetch()?:['avg_rating'=>0,'review_count'=>0];
$pend=q("SELECT s.session_id,s.session_date,sk.skill_name,IF(r.sender_id=?,ru.name,su.name) partner FROM sessions s JOIN requests r ON r.request_id=s.request_id JOIN skills sk ON sk.skill_id=r.skill_id JOIN users su ON su.user_id=r.sender_id JOIN users ru ON ru.user_id=r.receiver_id WHERE (r.sender_id=? OR r.receiver_id=?) AND s.session_date<=CURDATE() AND NOT EXISTS(SELECT 1 FROM reviews v WHERE v.session_id=s.session_id AND v.reviewer_id=?)",[$me,$me,$me,$me])->fetchAll();
out(true,'',['received'=>$recv,'average'=>round((float)$avg['avg_rating'],1),'count'=>(int)$avg['review_count'],'pending'=>$pend]);
