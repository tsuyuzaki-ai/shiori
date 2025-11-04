<?php
// ヘッダー 相対パス
require_once __DIR__ . '/header.php';

// データは public/list.php から受け取ります
// $mangas: 表示する漫画のリスト

// $searchKeyword: 検索キーワード（フォームの初期値用）
$searchKeyword = $_GET['search'] ?? '';
?>

<div class="list-search-box">
    <form action="<?php echo BASE_PATH; ?>/list.php" method="GET">
        <!-- value内には前回入力したキーワードを表示 -->
        <input type="text" name="search" placeholder="タイトルや作者名で検索..." value="<?php echo htmlspecialchars($searchKeyword); ?>">
        <button type="submit" class="btn">検索</button>
        <?php if ($searchKeyword): ?>
            <a href="<?php echo BASE_PATH; ?>/list.php" class="btn btn-secondary">クリア</a>
        <?php endif; ?>
    </form>
</div>

<div class="manga-list">
    <?php if (empty($mangas)): ?>
        <div class="list-empty-message">
            <p>漫画が登録されていません。</p>
            <p><a href="<?php echo BASE_PATH; ?>/search.php" class="btn">漫画を検索して追加</a></p>
        </div>
    <?php else: ?>
        <?php foreach ($mangas as $manga): ?>
            <div class="manga-item <?php echo $manga['is_completed'] ? 'completed' : ''; ?>">
                <div class="manga-info">
                    <div class="manga-title"><?php echo htmlspecialchars($manga['manga_name']); ?></div>
                    <div class="manga-author"><?php echo htmlspecialchars($manga['author_name']); ?></div>
                </div>
                <div class="manga-actions">
                    <div class="volume-control">
                        <button class="volume-btn" onclick="updateVolume('<?php echo htmlspecialchars($manga['manga_id']); ?>', -1)">-</button>
                        <span class="volume-value"><?php echo htmlspecialchars($manga['volume']); ?>巻</span>
                        <button class="volume-btn" onclick="updateVolume('<?php echo htmlspecialchars($manga['manga_id']); ?>', 1)">+</button>
                    </div>
                    <form action="<?php echo BASE_PATH; ?>/manga.php" method="POST" style="display: inline;">
                        <input type="hidden" name="action" value="toggle_completed">
                        <input type="hidden" name="manga_id" value="<?php echo htmlspecialchars($manga['manga_id']); ?>">
                        <button type="submit" class="btn" style="background: <?php echo $manga['is_completed'] ? '#28a745' : '#ffc107'; ?>">
                            <?php echo $manga['is_completed'] ? '読了済み' : '読了にする'; ?>
                        </button>
                    </form>
                    <form action="<?php echo BASE_PATH; ?>/manga.php" method="POST" style="display: inline;" onsubmit="return confirm('削除してもよろしいですか？');">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="manga_id" value="<?php echo htmlspecialchars($manga['manga_id']); ?>">
                        <button type="submit" class="manga-delete-btn">削除</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>

