<?php
// エントリーポイント: 新しい漫画検索ページ
// このファイルはURLから直接アクセスされます: http://localhost:8000/shiori/search.php

require_once __DIR__ . '/../src/bootstrap.php';

// ログインチェック
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_PATH . '/login.php');
    exit;
}

// 検索ページはデータ取得なし（検索はJavaScriptでAPI経由）
// ビューを表示するだけ
require_once __DIR__ . '/../src/views/search.php';

