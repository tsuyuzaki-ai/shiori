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

// データ処理（ビジネスロジック）
$action = $_POST['action'] ?? '';
$mangaModel = new Manga($pdo);
$userId = $_SESSION['user_id'];

if ($action === 'add') {
    $mangaId = $_POST['manga_id'] ?? '';
    $mangaName = $_POST['manga_name'] ?? '';
    $authorName = $_POST['author_name'] ?? '';
    $volume = intval($_POST['volume'] ?? 0);
    
    $mangaModel->create($userId, $mangaId, $mangaName, $authorName, $volume);
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

