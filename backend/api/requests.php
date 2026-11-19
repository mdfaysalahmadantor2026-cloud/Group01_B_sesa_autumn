<?php require '../includes/helpers.php';$me=need();$d=inp();
if($_SERVER['REQUEST_METHOD']==='POST'){
$a=$d['action']??'create';
if($a==='create'){$to=(int)($d['receiver_id']??0);$sk=(int)($d['skill_id']??0);
if($to===$me)out(false,'You cannot send a request to yourself',null,422);
if(!q('SELECT 1 FROM user_skills WHERE user_id=? AND skill_id=? AND skill_type=\'offered\'',[$to,$sk])->fetch())out(false,'That student does not offer this skill',null,422);
if(q("SELECT 1 FROM requests WHERE sender_id=? AND receiver_id=? AND skill_id=? AND status='pending'",[$me,$to,$sk])->fetch())out(false,'You already have a pending request for this skill',null,409);
q("INSERT INTO requests(sender_id,receiver_id,skill_id,status) VALUES(?,?,?,'pending')",[$me,$to,$sk]);
$n=q('SELECT name FROM users WHERE user_id=?',[$me])->fetchColumn();$sn=q('SELECT skill_name FROM skills WHERE skill_id=?',[$sk])->fetchColumn();
notify($to,"$n wants to learn $sn from you",'request');out(true,'Request sent',null,201);}
if(in_array($a,['accept','reject'])){
$r=q('SELECT * FROM requests WHERE request_id=? AND receiver_id=?',[(int)($d['id']??0),$me])->fetch();
if(!$r)out(false,'Request not found',null,404);if($r['status']!=='pending')out(false,'Already answered',null,409);
$st=$a==='accept'?'accepted':'rejected';q('UPDATE requests SET status=? WHERE request_id=?',[$st,$r['request_id']]);
$n=q('SELECT name FROM users WHERE user_id=?',[$me])->fetchColumn();
notify($r['sender_id'],"$n $st your request",$st==='accepted'?'accepted':'rejected');out(true,"Request $st");}
out(false,'Unknown action',null,400);}
$sql="SELECT r.request_id,r.status,r.requested_date,r.sender_id,r.receiver_id,su.name sender,ru.name receiver,sk.skill_name,(SELECT COUNT(*) FROM sessions s WHERE s.request_id=r.request_id) sessions FROM requests r JOIN users su ON su.user_id=r.sender_id JOIN users ru ON ru.user_id=r.receiver_id JOIN skills sk ON sk.skill_id=r.skill_id WHERE r.%s=? ORDER BY r.requested_date DESC,r.request_id DESC";
out(true,'',['sent'=>q(sprintf($sql,'sender_id'),[$me])->fetchAll(),'received'=>q(sprintf($sql,'receiver_id'),[$me])->fetchAll()]);
