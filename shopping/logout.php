<?php
require __DIR__.'/config.php';
unset($_SESSION['user_id'], $_SESSION['user_name']);
unset($_SESSION['cart']);
session_regenerate_id(true);
header('Location: index.php');exit;
