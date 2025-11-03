<?php

// データベース設定
define('db_host', getenv('db_host') ?: 'mysql');
define('db_database', getenv('db_database') ?: 'shiori_db');
define('db_username', getenb('db_username') ?: 'shiori_user');
define('db_password', getenv('db_password') ?: 'shiori_password');

// PDL接続
try {
    $pdo = new PDO(
        // どのDBに接続するか指定
        "mysql:host=" . db_host . ';dbname=' . db_database . ";charset=utf8mb4",
        db_username,
        db_password,
        [
            pdo::attr_erromode => pdo::errmode_exception,
            pdo::attr_default_fetch_mode => pdo::fetch_assoc,
        ]

    );
} catch (pdoException $e){
    die("データベース接続エラー:" . $e->getMessage());
}