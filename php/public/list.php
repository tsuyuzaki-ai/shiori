<?php
// エントリーポイント: 一覧ページ
// このファイルはURLから直接アクセスされます: http://localhost:8000/shiori/list.php

require_once __DIR__ . '/../src/bootstrap.php';

// ログインチェック
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_PATH . '/login.php');
    exit;
}

// データ取得（ビジネスロジック）
$userModel = new User($pdo);
$mangaModel = new Manga($pdo);

$searchKeyword = $_GET['search'] ?? '';

// 検索キーワードがある場合は検索、なければ全件取得
if ($searchKeyword) {
    $mangas = $mangaModel->searchByUserId($_SESSION['user_id'], $searchKeyword);
} else {
    $mangas = $mangaModel->findAllByUserId($_SESSION['user_id']);
}

// ビューにデータを渡して表示
require_once __DIR__ . '/../src/views/list.php';

