<?php require '../includes/helpers.php';$me=needAdmin();$d=inp();
if($_SERVER['REQUEST_METHOD']==='POST'){$a=$d['action']??'';
if($a==='delete_user'){$id=(int)($d['id']??0);if(!q("DELETE FROM users WHERE user_id=? AND role='student'",[$id])->rowCount())out(false,'Student not found',null,404);out(true,'Student deleted');}
if($a==='add_skill'){$n=s($d['name']??'',100);if(!$n)out(false,'Skill name required',null,422);
if(q('SELECT 1 FROM skills WHERE skill_name=?',[$n])->fetch())out(false,'Skill exists',null,409);q('INSERT INTO skills(skill_name) VALUES(?)',[$n]);out(true,'Skill added',null,201);}
if($a==='delete_skill'){q('DELETE FROM skills WHERE skill_id=?',[(int)($d['id']??0)]);out(true,'Skill deleted');}
out(false,'Unknown action',null,400);}
$c=fn($t,$w='1')=>(int)q("SELECT COUNT(*) FROM $t WHERE $w")->fetchColumn();
out(true,'',['stats'=>['students'=>$c('users',"role='student'"),'skills'=>$c('skills'),'requests'=>$c('requests'),'sessions'=>$c('sessions'),'reviews'=>$c('reviews')],
'users'=>q("SELECT user_id,name,email,university,department FROM users WHERE role='student' ORDER BY name")->fetchAll(),
'skills'=>q('SELECT skill_id,skill_name FROM skills ORDER BY skill_name')->fetchAll()]);
