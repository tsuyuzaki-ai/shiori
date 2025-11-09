<?php
require_once __DIR__ . '/../../src/bootstrap.php';

header('Content-Type: application/json; charset=utf-8');

// セッションチェック
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'ログインが必要です']);
    exit;
}

$keyword = $_GET['q'] ?? '';

if (empty($keyword)) {
    echo json_encode([]);
    exit;
}

// 1巻のみに絞り込むかどうか（オプションパラメータ）
$volume1Only = isset($_GET['volume1']) && $_GET['volume1'] === '1';

try {
    // 楽天ブックスAPIを使用した検索
    $openBDService = new OpenBDService();
    $results = $openBDService->search($keyword, 10, $volume1Only);
    
    echo json_encode($results, JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    error_log('Search API Error: ' . $e->getMessage());
    echo json_encode(['error' => '検索中にエラーが発生しました']);
}
exit;
