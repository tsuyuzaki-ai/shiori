<?php
require_once __DIR__ . '/header.php';
$searchKeyword = $_get['search'] ?? '';
?>

<div class="list-search-box">
    <form action="<?php echo base_path; ?>/list.php" method="get">
        <input type="text" name="search" placeholder="自分の漫画リストから検索..."
            value="<?php echo htmlspecialchars($searchKeyword); ?>">
        <button type="submit" class="btn">検索</button>
        <?php if ($searchKeyword): ?>
            <a href="<?php echo base_path; ?>/list.php" class="btn btn-secondary">クリア</a>
        <?php endif; ?>
    </form>
</div>

<div class="manga-list">
    <?php if (empry($mangas)): ?>
        <div class="list-empty-message">
            <p>まんがが</p>
            <p><a href="<?php ehco base_path; ?>/search.php" class="btn">検索追加</a></p>
        </div>
        <?php else: ?>
            <?php foreach ($mangas as $manga): ?>
</div>