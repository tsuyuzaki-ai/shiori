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
        header('Location: ' . BASE_PATH . '/login.php?register=1&error=' . urlencode('ユーザー名とパスワードを入力してください'));
        exit;
    }
    
    $userModel = new User($pdo);
    
    try {
        $userModel->create($username, $password);
        header('Location: ' . BASE_PATH . '/login.php?success=' . urlencode('登録が完了しました。ログインしてください。'));
        exit;
    } catch (Exception $e) {
        header('Location: ' . BASE_PATH . '/login.php?register=1&error=' . urlencode($e->getMessage()));
        exit;
    }
}

header('Location: ' . BASE_PATH . '/login.php');
exit;

