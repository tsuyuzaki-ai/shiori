/* ------------------------------------------
一覧ページ
------------------------------------------ */
// updateVolume('manga123', -1)
function updateVolume(mangaId, change) {
    // 巻数表示要素を取得
    const volumeElement = document.querySelector(`[data-manga-id="${mangaId}"] .volume-value`);
    if (!volumeElement) return;
    
    // ローディング状態（一時的に無効化）
    const volumeControl = volumeElement.closest('.volume-control');
    if (volumeControl) {
        volumeControl.style.opacity = '0.6';
        volumeControl.style.pointerEvents = 'none';
    }
    
    // フォームデータを作成
    const formData = new FormData();
    formData.append('action', 'update_volume');
    formData.append('manga_id', mangaId);
    formData.append('change', change);
    
    // Ajaxで更新
    fetch(BASE_PATH + '/manga.php', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // 巻数表示を更新
            if (data.volume === 0) {
                volumeElement.textContent = '未読';
            } else {
                volumeElement.textContent = data.volume + '巻';
            }
        } else {
            alert(data.error || '更新に失敗しました');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('更新中にエラーが発生しました');
    })
    .finally(() => {
        // ローディング状態を解除
        if (volumeControl) {
            volumeControl.style.opacity = '1';
            volumeControl.style.pointerEvents = 'auto';
        }
    });
}

/* ------------------------------------------
検索ページ
------------------------------------------ */
let searchTimeout;
const searchInput = document.getElementById('searchInput');
const resultsDiv = document.getElementById('results');

if (searchInput && resultsDiv) {
    // .addEventListener('input', …)→文字を打つたびに反応
    searchInput.addEventListener('input', function () {
        // this→searchInput
        const keyword = this.value.trim();

        clearTimeout(searchTimeout);

        if (keyword.length < 2) {
            resultsDiv.innerHTML = '';
            // 関数の処理を終了 breakはループを終了
            return;
        }
        // 入力のたびに検索するのを防ぐ setTImeout(処理, ⚪︎秒に一回)
        // function()→() =>
        searchTimeout = setTimeout(() => {
            searchManga(keyword);
        }, 300);
    });
}

function searchManga(keyword) {
    if (!resultsDiv) return;

    resultsDiv.innerHTML = '<div class="search-loading">検索中...</div>';

    // encodeURIComponent→%E3%83%9E%E3%83%B3%E3%82%AC%201%E5%B7%BB
    // fetch は必ず Promise を返す→then,catch
    fetch(BASE_PATH + '/api/search.php?q=' + encodeURIComponent(keyword))
    // データを受け取ってjsonに変換
    // （引数） => 処理
        .then(response => response.json())
        // jsonを使って処理
        .then(data => {
            if (data.error) {
                resultsDiv.innerHTML = '<div class="search-error-message">' + escapeHtml(data.error) + '</div>';
                return;
            }

            if (data.length === 0) {
                resultsDiv.innerHTML = '<div class="search-loading">見つかりませんでした</div>';
                return;
            }

            let html = '';
            data.forEach(item => {
                const coverImage = item.cover_image || '';
                const coverHtml = coverImage ? '<img src="' + escapeHtml(coverImage) + '" alt="' + escapeHtml(item.title) + '" class="search-result-cover">' : '';
                const coverImageEscaped = coverImage ? escapeHtml(coverImage) : '';
                html +=
                    '<div class="search-result-item" onclick="addManga(\'' +
                    item.manga_id + '\', \'' + escapeHtml(item.title) + '\', \'' + escapeHtml(item.author) + '\', 0, \'' + coverImageEscaped + '\')">' +
                    '<div class="search-result-content">' +
                    coverHtml +
                    '<div class="search-result-info">' +
                    '<div class="search-result-title">' + escapeHtml(item.title) + '</div>' +
                    '<div class="search-result-author">' + escapeHtml(item.author) + '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>';
            });
            resultsDiv.innerHTML = html;
        })
        .catch(error => {
            resultsDiv.innerHTML = '<div class="search-error-message">検索中にエラーが発生しました</div>';
        });
}

function addManga(mangaId, title, author, volume, coverImage) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = BASE_PATH + '/manga.php';

    const actionInput = document.createElement('input');
    actionInput.type = 'hidden';
    actionInput.name = 'action';
    actionInput.value = 'add';
    form.appendChild(actionInput);

    const mangaIdInput = document.createElement('input');
    mangaIdInput.type = 'hidden';
    mangaIdInput.name = 'manga_id';
    mangaIdInput.value = mangaId;
    form.appendChild(mangaIdInput);

    const titleInput = document.createElement('input');
    titleInput.type = 'hidden';
    titleInput.name = 'manga_name';
    titleInput.value = title;
    form.appendChild(titleInput);

    const authorInput = document.createElement('input');
    authorInput.type = 'hidden';
    authorInput.name = 'author_name';
    authorInput.value = author;
    form.appendChild(authorInput);

    const volumeInput = document.createElement('input');
    volumeInput.type = 'hidden';
    volumeInput.name = 'volume';
    volumeInput.value = volume || 0;
    form.appendChild(volumeInput);

    const coverImageInput = document.createElement('input');
    coverImageInput.type = 'hidden';
    coverImageInput.name = 'cover_image';
    coverImageInput.value = coverImage || '';
    form.appendChild(coverImageInput);

    document.body.appendChild(form);
    form.submit();
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

