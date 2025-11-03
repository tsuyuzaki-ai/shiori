<?php
// ベースパスの自動検出
function getBasePath() {
    // リクエストURIから判定
    $requestUri = $_SERVER['REQUEST_URI'] ?? '';
    
    // /shiori/public/ で始まる場合は本番環境
    if (strpos($requestUri, '/shiori/public/') === 0) {
        return '/shiori/public';
    }
    
    // /shiori/ で始まる場合は開発環境
    if (strpos($requestUri, '/shiori/') === 0) {
        return '/shiori';
    }
    
    // デフォルトは /shiori
    return '/shiori';
}

// ベースパスを定数として定義
if (!defined('BASE_PATH')) {
    define('BASE_PATH', getBasePath());
}

