<?php
require_once __DIR__ . '/../../src/bootstrap.php';

header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'ログインが必要です']);
    exit;
}

$keyword = $_GET['q'] ?? '';

if (empty($keyword)) {
    echo json_encode([]);
    exit;
}

// 楽天ブックスAPIを使用した検索
$openBDService = new OpenBDService();
$results = $openBDService->search($keyword, 10);

echo json_encode($results);
exit;

