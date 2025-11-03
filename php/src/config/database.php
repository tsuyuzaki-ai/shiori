<?php
// データベース設定
define('DB_HOST', getenv('DB_HOST') ?: 'mysql');
define('DB_DATABASE', getenv('DB_DATABASE') ?: 'shiori_db');
define('DB_USERNAME', getenv('DB_USERNAME') ?: 'shiori_user');
define('DB_PASSWORD', getenv('DB_PASSWORD') ?: 'shiori_password');

// PDO接続
try {
    $pdo = new PDO(
        // どのDBに接続するか指定
        "mysql:host=" . DB_HOST . ";dbname=" . DB_DATABASE . ";charset=utf8mb4",
        DB_USERNAME,
        DB_PASSWORD,
        // オプション データを配列にする
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    die("データベース接続エラー: " . $e->getMessage());
}

