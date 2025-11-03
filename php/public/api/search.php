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

// openBD APIを使用した検索
// 注意: openBD APIはISBN検索のため、実際の実装では別のAPIやデータベースが必要
// ここではサンプル実装として、簡易的な検索結果を返す

$openBDService = new OpenBDService();

// 実際の実装では、openBD APIや他の漫画検索APIを使用
// 今回はサンプルとして空の配列を返す
$results = $openBDService->search($keyword, 10);

// サンプルデータ（実際の実装では削除）
$sampleResults = [
    [
        'manga_id' => 'sample_1',
        'title' => 'サンプル漫画 1',
        'author' => 'サンプル作者',
        'volume' => 1
    ],
    [
        'manga_id' => 'sample_2',
        'title' => 'サンプル漫画 2',
        'author' => 'サンプル作者',
        'volume' => 5
    ]
];

// キーワードが含まれるかチェック（簡易実装）
$filteredResults = array_filter($sampleResults, function($item) use ($keyword) {
    return stripos($item['title'], $keyword) !== false || 
           stripos($item['author'], $keyword) !== false;
});

echo json_encode(array_values($filteredResults));
exit;

