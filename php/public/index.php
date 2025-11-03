<?php
// アプリケーションのエントリーポイント
require_once __DIR__ . '/../src/bootstrap.php';

// セッションにログイン情報があれば一覧へ、なければログインへ
if (isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_PATH . '/list.php');
} else {
    header('Location: ' . BASE_PATH . '/login.php');
}
exit;
