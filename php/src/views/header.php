<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shiori - マンガ巻数管理アプリ</title>
    <link rel="stylesheet" href="<?php echo BASE_PATH; ?>/css/style.css">
    <script>
        const BASE_PATH = '<?php echo BASE_PATH; ?>';
    </script>
</head>
<body>
    <div class="container">
        <header>
            <nav>
                <div>
                    <a href="<?php echo BASE_PATH; ?>/list.php">一覧</a>
                    <a href="<?php echo BASE_PATH; ?>/search.php">検索</a>
                </div>
                <div class="user-info">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <span><?php echo htmlspecialchars($_SESSION['username']); ?>さん</span>
                        <a href="<?php echo BASE_PATH; ?>/logout.php" class="btn btn-secondary">ログアウト</a>
                    <?php else: ?>
                        <a href="<?php echo BASE_PATH; ?>/login.php" class="btn">ログイン</a>
                    <?php endif; ?>
                </div>
            </nav>
        </header>

