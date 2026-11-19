<?php
try{$pdo=new PDO('mysql:host=localhost;dbname=skillswap;charset=utf8mb4','root','',[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,PDO::ATTR_EMULATE_PREPARES=>false]);}
catch(PDOException $e){http_response_code(500);header('Content-Type: application/json');echo json_encode(['success'=>false,'message'=>'Database connection failed','data'=>null]);exit;}
