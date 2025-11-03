<?php
require_once __DIR__ . '/../src/bootstrap.php';

session_destroy();
header('Location: ' . BASE_PATH . '/login.php');
exit;

