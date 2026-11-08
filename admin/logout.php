<?php
require_once __DIR__ . '/../config.php';

$_SESSION['admin_logged_in'] = false;
unset($_SESSION['admin_logged_in']);
unset($_SESSION['admin_username']);
session_destroy();

header('Location: index.php');
exit;
