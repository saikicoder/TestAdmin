<?php
session_start();
require_once 'includes/config.php';
require_once 'classes/User.php';

$user = new User($pdo);
$user->logout();

header('Location: index.php');
exit;
?>
