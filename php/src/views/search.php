<?php
// ビューファイル: 検索ページのHTML表示
// このファイルは public/search.php から呼び出されます
// 検索機能はJavaScriptで実装されているため、データ取得は不要です

require_once __DIR__ . '/header.php';
?>

<div class="search-container">
    <h1>漫画を検索</h1>
    
    <div class="search-page-box">
        <input type="text" id="searchInput" placeholder="タイトルや作者名を入力..." autocomplete="off">
    </div>
    
    <div id="results" class="search-results"></div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
