<?php
// アプリケーション設定
define('BASE_URL', getenv('BASE_URL') ?: 'http://localhost:8000');

// 楽天ブックスAPI設定
define('RAKUTEN_APP_ID', '1044056387719677346');
define('RAKUTEN_APP_SECRET', '3f94c65aa7f1df6784c808dbc5c45c9da5199b02');
define('RAKUTEN_BOOKS_API_URL', 'https://app.rakuten.co.jp/services/api/BooksBook/Search/20170404');

// タイムゾーン設定
date_default_timezone_set('Asia/Tokyo');

// エラーレポート設定
error_reporting(E_ALL);
ini_set('display_errors', 1);

