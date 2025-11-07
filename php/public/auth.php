<?php
// エントリーポイント: 認証処理
// このファイルはログイン/登録フォームから送信されます
// ビューは表示せず、処理のみを行います

require_once __DIR__ . '/../src/bootstrap.php';

$action = $_POST['action'] ?? '';

if ($action === 'login') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $userModel = new User($pdo);
    $user = $userModel->authenticate($username, $password);
    
    if ($user) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        // header('Location: ...')→別ページへ移動
        header('Location: ' . BASE_PATH . '/list.php');
        exit;
    } else {
        header('Location: ' . BASE_PATH . '/login.php?error=' . urlencode('ユーザー名またはパスワードが正しくありません'));
        exit;
    }
} elseif ($action === 'register') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        // urlencode → スペース を %20 にする 安全のため
        header('Location: ' . BASE_PATH . '/login.php?register=1&error=' . urlencode('ユーザー名とパスワードを入力してください'));
        exit;
    }
    
    $userModel = new User($pdo);
    
    try {
        $userModel->create($username, $password);
        // 登録完了後、自動ログインして一覧ページへ移動
        $user = $userModel->authenticate($username, $password);
        if ($user) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            header('Location: ' . BASE_PATH . '/list.php');
            exit;
        } else {
            // 通常は発生しないが、念のため
            header('Location: ' . BASE_PATH . '/login.php?error=' . urlencode('登録に失敗しました'));
            exit;
        }
    } catch (Exception $e) {
        header('Location: ' . BASE_PATH . '/login.php?register=1&error=' . urlencode($e->getMessage()));
        exit;
    }
}

header('Location: ' . BASE_PATH . '/login.php');
exit;

