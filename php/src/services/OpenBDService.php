<?php

class OpenBDService
{
    // 楽天ブックスAPIを使用して漫画を検索
    public function search($keyword, $limit = 10)
    {
        // 楽天ブックスAPIのエンドポイント
        $url = RAKUTEN_BOOKS_API_URL;
        
        // パラメータを設定（booksGenreIdは無効なパラメータのため削除）
        $params = [
            'applicationId' => RAKUTEN_APP_ID,
            'keyword' => $keyword,
            'format' => 'json',
            'formatVersion' => 2,
            'hits' => $limit * 3 // コミック以外をフィルタリングするため多めに取得
        ];
        
        $url .= '?' . http_build_query($params);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);
        
        if ($httpCode !== 200 || !$response) {
            error_log('Rakuten API Error: HTTP ' . $httpCode . ' - ' . $curlError);
            return [];
        }
        
        $data = json_decode($response, true);
        
        // エラーチェック
        if (isset($data['error'])) {
            error_log('Rakuten API Error: ' . json_encode($data['error']));
            return [];
        }
        
        if (!isset($data['Items']) || !is_array($data['Items']) || empty($data['Items'])) {
            return [];
        }
        
        $results = [];
        foreach ($data['Items'] as $item) {
            // formatVersion 2の場合、Itemキーは存在せず、直接アイテムデータが返る
            if (!is_array($item)) {
                continue;
            }
            
            // コミックカテゴリかどうかを確認
            // 楽天ブックスのコミックジャンルIDは「001001」で始まる（例: 001001003021）
            $booksGenreId = isset($item['booksGenreId']) ? (string)$item['booksGenreId'] : '';
            
            // 001001で始まるジャンルIDはコミック
            if (empty($booksGenreId) || strpos($booksGenreId, '001001') !== 0) {
                continue;
            }
            
            // タイトルと著者を取得
            $title = isset($item['title']) ? trim($item['title']) : '';
            $author = isset($item['author']) ? trim($item['author']) : '';
            $isbn = isset($item['isbn']) ? trim($item['isbn']) : '';
            
            // タイトルが空の場合はスキップ
            if (empty($title)) {
                continue;
            }
            
            // 表紙画像を取得（大→中→小の順で優先）
            $coverImage = '';
            if (!empty($item['largeImageUrl'])) {
                $coverImage = $item['largeImageUrl'];
            } elseif (!empty($item['mediumImageUrl'])) {
                $coverImage = $item['mediumImageUrl'];
            } elseif (!empty($item['smallImageUrl'])) {
                $coverImage = $item['smallImageUrl'];
            }
            
            // manga_idはISBNを使用（ISBNがない場合はタイトルと著者から生成）
            $mangaId = !empty($isbn) ? $isbn : md5($title . $author);
            
            $results[] = [
                'manga_id' => $mangaId,
                'title' => $title,
                'author' => $author,
                'cover_image' => $coverImage
            ];
            
            // 必要な数だけ取得したら終了
            if (count($results) >= $limit) {
                break;
            }
        }
        
        return $results;
    }

    // ISBNから書籍情報を取得（楽天ブックスAPI）
    public function getBookInfo($isbn)
    {
        $url = RAKUTEN_BOOKS_API_URL;
        
        $params = [
            'applicationId' => RAKUTEN_APP_ID,
            'isbn' => $isbn,
            'format' => 'json',
            'formatVersion' => 2
        ];
        
        $url .= '?' . http_build_query($params);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode !== 200 || !$response) {
            return null;
        }

        $data = json_decode($response, true);
        
        if (isset($data['error']) || !isset($data['Items']) || empty($data['Items'])) {
            return null;
        }
        
        // formatVersion 2の場合、Itemキーは存在しない
        if (!isset($data['Items'][0]) || !is_array($data['Items'][0])) {
            return null;
        }
        
        $bookItem = $data['Items'][0];
        
        // 表紙画像を取得
        $coverImage = '';
        if (!empty($bookItem['largeImageUrl'])) {
            $coverImage = $bookItem['largeImageUrl'];
        } elseif (!empty($bookItem['mediumImageUrl'])) {
            $coverImage = $bookItem['mediumImageUrl'];
        } elseif (!empty($bookItem['smallImageUrl'])) {
            $coverImage = $bookItem['smallImageUrl'];
        }
        
        return [
            'isbn' => isset($bookItem['isbn']) ? $bookItem['isbn'] : $isbn,
            'title' => isset($bookItem['title']) ? $bookItem['title'] : '',
            'author' => isset($bookItem['author']) ? $bookItem['author'] : '',
            'cover_image' => $coverImage
        ];
    }
}
