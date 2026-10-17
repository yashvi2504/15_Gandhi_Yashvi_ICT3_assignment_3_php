<?php
$url='http://127.0.0.1:3000/api/message';
$payload=json_encode(['name'=>'PHP client','message'=>'Hello from PHP']);
$ch=curl_init($url);
curl_setopt_array($ch,[CURLOPT_RETURNTRANSFER=>true,CURLOPT_POST=>true,CURLOPT_HTTPHEADER=>['Content-Type: application/json'],CURLOPT_POSTFIELDS=>$payload,CURLOPT_TIMEOUT=>5]);
$response=curl_exec($ch);$error=curl_error($ch);$status=curl_getinfo($ch,CURLINFO_HTTP_CODE);curl_close($ch);
?><!doctype html><html><body><h1>PHP calling Express REST API</h1>
<?php if($response===false): ?><p>cURL error: <?= htmlspecialchars($error) ?></p><?php else: ?><p>HTTP status: <?= (int)$status ?></p><pre><?= htmlspecialchars($response) ?></pre><?php endif; ?></body></html>
