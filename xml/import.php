<?php
require __DIR__.'/../shopping/config.php';
$file=__DIR__.'/students.xml';
if(!file_exists($file))exit('students.xml not found');
libxml_use_internal_errors(true);$xml=simplexml_load_file($file);
if(!$xml)exit('Invalid XML file');
$insert=$pdo->prepare('INSERT INTO students(name,email) VALUES(?,?)');$count=0;
foreach($xml->student as $st){$name=trim((string)$st->name);$email=filter_var((string)$st->email,FILTER_VALIDATE_EMAIL);if($name!==''&&$email){$insert->execute([$name,$email]);$count++;}}
echo "Imported $count students into MySQL.";
