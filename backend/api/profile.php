<?php require '../includes/helpers.php';$me=need();$d=inp();
if($_SERVER['REQUEST_METHOD']==='POST'){
$n=s($d['name']??'',100);if(!$n)out(false,'Name is required',null,422);
global $pdo;$pdo->beginTransaction();
q('UPDATE users SET name=?,university=?,department=? WHERE user_id=?',[$n,s($d['university']??'',150),s($d['department']??'',150),$me]);
q('DELETE FROM user_phones WHERE user_id=?',[$me]);
foreach(array_slice((array)($d['phones']??[]),0,5) as $p){$p=s($p,30);if($p!=='')q('INSERT INTO user_phones(user_id,phone) VALUES(?,?)',[$me,$p]);}
q('DELETE FROM user_skills WHERE user_id=?',[$me]);
foreach(array_slice((array)($d['skills']??[]),0,60) as $k){$t=$k['type']??'';$l=$k['level']??'Beginner';
if(!in_array($t,['offered','wanted'])||!in_array($l,['Beginner','Intermediate','Advanced']))continue;
if(!q('SELECT 1 FROM skills WHERE skill_id=?',[(int)($k['skill_id']??0)])->fetch())continue;
q('INSERT IGNORE INTO user_skills(user_id,skill_id,skill_type,proficiency) VALUES(?,?,?,?)',[$me,(int)$k['skill_id'],$t,$l]);}
q('DELETE FROM availability WHERE user_id=?',[$me]);
foreach(array_slice((array)($d['availability']??[]),0,21) as $a){
if(!in_array($a['day']??'',['Mon','Tue','Wed','Thu','Fri','Sat','Sun'])||!preg_match('/^\d\d:\d\d/',$a['start']??'')||!preg_match('/^\d\d:\d\d/',$a['end']??'')||$a['end']<=$a['start'])continue;
q('INSERT INTO availability(user_id,day_of_week,start_time,end_time) VALUES(?,?,?,?)',[$me,$a['day'],$a['start'],$a['end']]);}
$pdo->commit();out(true,'Profile saved');}
$id=(int)($d['id']??$me);
$u=q('SELECT u.user_id,u.name,u.university,u.department,COALESCE(r.avg_rating,0) rating,COALESCE(r.review_count,0) reviews FROM users u LEFT JOIN user_ratings r ON r.user_id=u.user_id WHERE u.user_id=? AND u.role=\'student\'',[$id])->fetch();
if(!$u)out(false,'User not found',null,404);
$u['phones']=$id===$me?q('SELECT phone FROM user_phones WHERE user_id=?',[$id])->fetchAll(PDO::FETCH_COLUMN):[];
$u['skills']=q('SELECT us.skill_id,s.skill_name,us.skill_type type,us.proficiency level FROM user_skills us JOIN skills s ON s.skill_id=us.skill_id WHERE us.user_id=?',[$id])->fetchAll();
$u['availability']=q("SELECT day_of_week day,TIME_FORMAT(start_time,'%H:%i') start,TIME_FORMAT(end_time,'%H:%i') end FROM availability WHERE user_id=? ORDER BY FIELD(day_of_week,'Mon','Tue','Wed','Thu','Fri','Sat','Sun'),start_time",[$id])->fetchAll();
$u['is_me']=$id===$me;out(true,'',$u);
