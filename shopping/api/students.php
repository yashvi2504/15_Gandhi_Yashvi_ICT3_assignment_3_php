<?php
require __DIR__.'/../config.php';
header('Content-Type: application/json; charset=utf-8');
$method=$_SERVER['REQUEST_METHOD'];
if($method==='GET'){
 $rows=$pdo->query('SELECT id,name,email FROM students ORDER BY id')->fetchAll();
 echo json_encode(['success'=>true,'data'=>$rows]); exit;
}
if($method==='POST'){
 $body=json_decode(file_get_contents('php://input'),true) ?? [];
 $name=trim($body['name']??'');$email=filter_var($body['email']??'',FILTER_VALIDATE_EMAIL);
 if($name===''||!$email){http_response_code(400);echo json_encode(['success'=>false,'message'=>'name and valid email required']);exit;}
 $s=$pdo->prepare('INSERT INTO students(name,email) VALUES(?,?)');$s->execute([$name,$email]);
 http_response_code(201);echo json_encode(['success'=>true,'id'=>(int)$pdo->lastInsertId()]);exit;
}
http_response_code(405);echo json_encode(['success'=>false,'message'=>'Method not allowed']);
