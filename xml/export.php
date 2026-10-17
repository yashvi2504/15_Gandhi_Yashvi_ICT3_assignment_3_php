<?php
require __DIR__.'/../shopping/config.php';
$rows=$pdo->query('SELECT name,email FROM students ORDER BY id')->fetchAll();
$xml=new XMLWriter();$xml->openMemory();$xml->startDocument('1.0','UTF-8');$xml->setIndent(true);$xml->startElement('students');
foreach($rows as $r){$xml->startElement('student');$xml->writeElement('name',$r['name']);$xml->writeElement('email',$r['email']);$xml->endElement();}
$xml->endElement();$xml->endDocument();
header('Content-Type: application/xml; charset=utf-8');header('Content-Disposition: attachment; filename="students.xml"');echo $xml->outputMemory();
