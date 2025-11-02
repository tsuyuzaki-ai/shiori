<?php
// アプリケーション設定

// ベースURL（ローカル開発時）
define('BASE_URL', 'http://localhost:8000');

// openBD API URL
define('OPENBD_API_URL', 'https://api.openbd.jp/v1/get');

// タイムゾーン設定
date_default_timezone_set('Asia/Tokyo');

// エラーレポート設定（開発環境）
error_reporting(E_ALL);
ini_set('display_errors', 1);

