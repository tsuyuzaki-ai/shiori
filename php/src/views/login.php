<?php
require_once __DIR__ . '/header.php';
?>

<div class="login-container">
    <h1>Shiori</h1>
    
    <?php if (isset($_GET['error'])): ?>
        <div class="login-error"><?php echo htmlspecialchars($_GET['error']); ?></div>
    <?php endif; ?>
    
    <?php if (isset($_GET['success'])): ?>
        <div class="login-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
    <?php endif; ?>
    
    <?php if (!isset($_GET['register'])): ?>
        <!-- ログイン Form -->
        <form action="<?php echo BASE_PATH; ?>/auth.php" method="POST">
            <input type="hidden" name="action" value="login">
            <div class="login-form-group">
                <label for="username">ユーザー名</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="login-form-group">
                <label for="password">パスワード</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn" style="width: 100%;">ログイン</button>
        </form>
        
        <div class="toggle-form">
            アカウントをお持ちでない方は <a href="<?php echo BASE_PATH; ?>/login.php?register=1">新規登録</a>
        </div>
    <?php else: ?>
        <!-- 新規登録 Form -->
        <form action="<?php echo BASE_PATH; ?>/auth.php" method="POST">
            <input type="hidden" name="action" value="register">
            <div class="login-form-group">
                <label for="username">ユーザー名</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="login-form-group">
                <label for="password">パスワード</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn" style="width: 100%;">新規登録</button>
        </form>
        
        <div class="toggle-form">
            すでにアカウントをお持ちの方は <a href="<?php echo BASE_PATH; ?>/login.php">ログイン</a>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>

