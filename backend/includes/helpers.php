<?php
session_start();
require_once __DIR__.'/../config/db.php';
header('Content-Type: application/json; charset=utf-8');
set_exception_handler(function($e){out(false,'Server error',null,500);});
function out($ok,$msg='',$data=null,$code=200){http_response_code($code);echo json_encode(['success'=>$ok,'message'=>$msg,'data'=>$data]);exit;}
function q($sql,$p=[]){global $pdo;$s=$pdo->prepare($sql);$s->execute($p);return $s;}
function need(){if(empty($_SESSION['uid']))out(false,'Login required',null,401);return (int)$_SESSION['uid'];}
function needAdmin(){$u=need();if(($_SESSION['role']??'')!=='admin')out(false,'Admins only',null,403);return $u;}
function inp(){static $j=null;if($j===null)$j=json_decode(file_get_contents('php://input'),true)?:[];return $j+$_GET;}
function post(){if($_SERVER['REQUEST_METHOD']!=='POST')out(false,'POST required',null,405);}
function s($v,$m=255){return mb_substr(trim(strip_tags((string)$v)),0,$m);}
function notify($uid,$msg,$type){q('INSERT INTO notifications(user_id,message,type) VALUES(?,?,?)',[$uid,s($msg,500),$type]);}
function matches($u,$term=''){
$mw=q("SELECT skill_id FROM user_skills WHERE user_id=? AND skill_type='wanted'",[$u])->fetchAll(PDO::FETCH_COLUMN);
$mo=q("SELECT skill_id FROM user_skills WHERE user_id=? AND skill_type='offered'",[$u])->fetchAll(PDO::FETCH_COLUMN);
$rows=q("SELECT u.user_id,u.name,u.university,u.department,COALESCE(r.avg_rating,0) rating,COALESCE(r.review_count,0) reviews,us.skill_id,s.skill_name,us.skill_type,us.proficiency FROM users u LEFT JOIN user_skills us ON us.user_id=u.user_id LEFT JOIN skills s ON s.skill_id=us.skill_id LEFT JOIN user_ratings r ON r.user_id=u.user_id WHERE u.role='student' AND u.user_id<>?",[$u])->fetchAll();
$by=[];
foreach($rows as $r){$id=$r['user_id'];$by[$id]??=['user_id'=>$id,'name'=>$r['name'],'university'=>$r['university'],'department'=>$r['department'],'rating'=>round($r['rating'],1),'reviews'=>(int)$r['reviews'],'offers'=>[],'wants'=>[]];
if($r['skill_id']){$k=$r['skill_type']==='offered'?'offers':'wants';$by[$id][$k][]=['skill_id'=>(int)$r['skill_id'],'skill_name'=>$r['skill_name'],'proficiency'=>$r['proficiency']];}}
$out=[];$t=mb_strtolower($term);
foreach($by as $x){
if($t!==''){$hay=mb_strtolower($x['name'].' '.$x['department'].' '.implode(' ',array_column($x['offers'],'skill_name')));if(mb_strpos($hay,$t)===false)continue;}
$x['match']=(bool)array_intersect(array_column($x['offers'],'skill_id'),$mw);
$x['perfect']=$x['match']&&(bool)array_intersect(array_column($x['wants'],'skill_id'),$mo);
$out[]=$x;}
usort($out,fn($a,$b)=>[$b['perfect'],$b['match'],$b['rating']]<=>[$a['perfect'],$a['match'],$a['rating']]);
return $out;}
