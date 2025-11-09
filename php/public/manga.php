<?php
// エントリーポイント: 漫画のCRUD処理
// このファイルはフォーム送信などから呼び出されます
// ビューは表示せず、処理のみを行います

require_once __DIR__ . '/../src/bootstrap.php';

// ログインチェック
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_PATH . '/login.php');
    exit;
}

// タイトルから巻数部分を除去する関数
function removeVolumeFromTitle($title) {
    if (empty($title)) {
        return $title;
    }
    
    // より確実なパターンマッチング（順序が重要）
    // パターン1: 末尾の「（数字）」を除去（全角括弧）
    if (preg_match("/^(.+)[（][0-9]+[）]$/u", $title, $matches)) {
        return trim($matches[1]);
    }
    
    // パターン2: 末尾の「(数字)」を除去（半角括弧）
    if (preg_match("/^(.+)\\([0-9]+\\)$/u", $title, $matches)) {
        return trim($matches[1]);
    }
    
    // パターン3: 末尾の「 第数字巻」を除去
    if (preg_match("/^(.+)\\s+第[0-9]+[巻冊]$/u", $title, $matches)) {
        return trim($matches[1]);
    }
    
    // パターン4: 末尾の「 数字巻」を除去
    if (preg_match("/^(.+)\\s+[0-9]+[巻冊]$/u", $title, $matches)) {
        return trim($matches[1]);
    }
    
    // パターン5: 末尾の「 数字」を除去
    if (preg_match("/^(.+)\\s+[0-9]+$/u", $title, $matches)) {
        return trim($matches[1]);
    }
    
    return trim($title);
}

// データ処理（ビジネスロジック）
$action = $_POST['action'] ?? '';
$mangaModel = new Manga($pdo);
$userId = $_SESSION['user_id'];

// Ajaxリクエストかどうかを判定
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

if ($action === 'add') {
    $mangaId = $_POST['manga_id'] ?? '';
    $mangaName = $_POST['manga_name'] ?? '';
    $authorName = $_POST['author_name'] ?? '';
    $volume = intval($_POST['volume'] ?? 0);
    $coverImage = $_POST['cover_image'] ?? null;
    
    // タイトルから巻数部分を除去（必ず実行）
    $originalMangaName = $mangaName;
    $mangaName = removeVolumeFromTitle($mangaName);
    
    // デバッグ用ログ
    error_log('Manga add - Original: [' . $originalMangaName . '], Cleaned: [' . $mangaName . ']');
    
    // タイトルが空でないことを確認
    if (empty($mangaName)) {
        $mangaName = $originalMangaName; // 除去後に空になった場合は元のタイトルを使用
    }
    
    $mangaModel->create($userId, $mangaId, $mangaName, $authorName, $volume, $coverImage);
    header('Location: ' . BASE_PATH . '/list.php');
    exit;
} elseif ($action === 'update_volume') {
    $mangaId = $_POST['manga_id'] ?? '';
    $change = intval($_POST['change'] ?? 0);
    
    // 現在の巻数を取得
    $mangas = $mangaModel->findAllByUserId($userId);
    $currentManga = null;
    foreach ($mangas as $manga) {
        if ($manga['manga_id'] === $mangaId) {
            $currentManga = $manga;
            break;
        }
    }
    
    if ($currentManga) {
        $newVolume = max(0, $currentManga['volume'] + $change);
        $mangaModel->update($mangaId, $userId, $newVolume);
        
        // Ajaxリクエストの場合はJSONを返す
        if ($isAjax) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'success' => true,
                'manga_id' => $mangaId,
                'volume' => $newVolume
            ]);
            exit;
        }
    } else {
        // Ajaxリクエストでエラーの場合
        if ($isAjax) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'success' => false,
                'error' => '漫画が見つかりませんでした'
            ]);
            exit;
        }
    }
    
    header('Location: ' . BASE_PATH . '/list.php');
    exit;
} elseif ($action === 'toggle_completed') {
    $mangaId = $_POST['manga_id'] ?? '';
    
    // 現在の状態を取得
    $mangas = $mangaModel->findAllByUserId($userId);
    $currentManga = null;
    foreach ($mangas as $manga) {
        if ($manga['manga_id'] === $mangaId) {
            $currentManga = $manga;
            break;
        }
    }
    
    if ($currentManga) {
        // 現状を！で反転させる
        $newCompleted = !$currentManga['is_completed'];
        $mangaModel->update($mangaId, $userId, null, $newCompleted);
    }
    
    header('Location: ' . BASE_PATH . '/list.php');
    exit;
} elseif ($action === 'delete') {
    $mangaId = $_POST['manga_id'] ?? '';
    $mangaModel->delete($mangaId, $userId);
    header('Location: ' . BASE_PATH . '/list.php');
    exit;
}

header('Location: ' . BASE_PATH . '/list.php');
exit;
