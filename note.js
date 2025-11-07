// 一覧ページ
function updateVolume(mangaId, change){
    const form = documente.createElement('form');
    form.method = 'POST';
    form.action = BASE_PATH + '/manga.php';

    const actionInput = document.createElement('input');
    actionInput.type ='hidden';
    actionInput.name = 'action';
    actionInput.value = 'update_volume';
}