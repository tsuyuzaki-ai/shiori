<?php
// .envファイルを読み込む関数
function loadEnv($filePath)
{
    if (!file_exists($filePath)) {
        return;
    }

    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // コメント行をスキップ
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        // キー=値の形式を解析
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            
            // クォートを削除
            $value = trim($value, '"\'');
            
            // 環境変数が未設定の場合のみ設定
            if (!isset($_ENV[$key]) && !getenv($key)) {
                $_ENV[$key] = $value;
                putenv("$key=$value");
            }
        }
    }
}

// .envファイルを読み込む
$envPath = __DIR__ . '/../../.env';
loadEnv($envPath);

// 環境の取得（デフォルトはlocal）
define('APP_ENV', getenv('APP_ENV') ?: 'local');

