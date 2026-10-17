<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$host = 'localhost';
$db   = 'php_practical';
$user = 'root';
$pass = 'root123'; // XAMPP default; change if your MySQL has a password
$charset = 'utf8mb4';
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  PDO::ATTR_EMULATE_PREPARES => false,
];
try { $pdo = new PDO($dsn, $user, $pass, $options); }
catch (PDOException $e) { http_response_code(500); exit('Database connection failed. Check config.php and MySQL.'); }

function h($value) { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
function require_admin() {
  if (empty($_SESSION['admin_id'])) { header('Location: login.php'); exit; }
}
function require_user() {
  if (empty($_SESSION['user_id'])) { header('Location: login.php'); exit; }
}
