<?php
// オートローダーの読み込み
require_once __DIR__ . '/../vendor/autoload.php';

// 設定ファイルの読み込み
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/app.php';

// モデルクラスの読み込み
require_once __DIR__ . '/models/User.php';
require_once __DIR__ . '/models/Manga.php';

// サービスクラスの読み込み
require_once __DIR__ . '/services/OpenBDService.php';

// セッション開始
session_start();

// ルーティング処理（簡易版）
// ここは後でルータークラスに置き換える可能性があります

