/* ------------------------------------------
一覧ページ
------------------------------------------ */
// updateVolume('manga123', -1)
function updateVolume(mangaId, change) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = BASE_PATH + '/manga.php';
    
    const actionInput = document.createElement('input');
    actionInput.type = 'hidden';
    actionInput.name = 'action';
    actionInput.value = 'update_volume';
    form.appendChild(actionInput);
    
    const mangaIdInput = document.createElement('input');
    mangaIdInput.type = 'hidden';
    mangaIdInput.name = 'manga_id';
    mangaIdInput.value = mangaId;
    form.appendChild(mangaIdInput);
    
    const changeInput = document.createElement('input');
    changeInput.type = 'hidden';
    changeInput.name = 'change';
    changeInput.value = change;
    form.appendChild(changeInput);
    
    document.body.appendChild(form);
    form.submit();
}

/* ------------------------------------------
検索ページ
------------------------------------------ */
let searchTimeout;
const searchInput = document.getElementById('searchInput');
const resultsDiv = document.getElementById('results');

if (searchInput && resultsDiv) {
    searchInput.addEventListener('input', function() {
        const keyword = this.value.trim();
        
        clearTimeout(searchTimeout);
        
        if (keyword.length < 2) {
            resultsDiv.innerHTML = '';
            return;
        }
        
        searchTimeout = setTimeout(() => {
            searchManga(keyword);
        }, 300);
    });
}

function searchManga(keyword) {
    if (!resultsDiv) return;
    
    resultsDiv.innerHTML = '<div class="search-loading">検索中...</div>';
    
    fetch(BASE_PATH + '/api/search.php?q=' + encodeURIComponent(keyword))
        .then(response => response.json())
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
                html +=
                    '<div class="search-result-item" onclick="addManga(\'' + 
                    item.manga_id + '\', \'' + escapeHtml(item.title) + '\', \'' + escapeHtml(item.author) + '\', ' + 
                    item.volume + ')">' +
                    '<div class="search-result-title">' + escapeHtml(item.title) + '</div>' +
                    '<div class="search-result-author">' + escapeHtml(item.author) + ' - ' + item.volume + '巻</div>' +
                    '</div>';
            });
            resultsDiv.innerHTML = html;
        })
        .catch(error => {
            resultsDiv.innerHTML = '<div class="search-error-message">検索中にエラーが発生しました</div>';
        });
}

function addManga(mangaId, title, author, volume) {
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
    
    document.body.appendChild(form);
    form.submit();
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

