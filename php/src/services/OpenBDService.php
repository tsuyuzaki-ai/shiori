<?php

class OpenBDService
{
    // 楽天ブックスAPIを使用して漫画を検索
    public function search($keyword, $limit = 10)
    {
        // 楽天ブックスAPIのエンドポイント
        $url = RAKUTEN_BOOKS_API_URL;
        
        // パラメータを設定
        $params = [
            'applicationId' => RAKUTEN_APP_ID,
            'keyword' => $keyword,
            'format' => 'json',
            'formatVersion' => 2,
            'hits' => $limit,
            'booksGenreId' => '101299' // コミック
        ];
        
        $url .= '?' . http_build_query($params);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode !== 200 || !$response) {
            return [];
        }
        
        $data = json_decode($response, true);
        
        // エラーチェック
        if (isset($data['error'])) {
            return [];
        }
        
        if (!isset($data['Items']) || empty($data['Items'])) {
            return [];
        }
        
        $results = [];
        foreach ($data['Items'] as $item) {
            $bookItem = $item['Item'] ?? [];
            
            if (empty($bookItem)) {
                continue;
            }
            
            // コミックカテゴリかどうかを確認
            $booksGenreId = $bookItem['booksGenreId'] ?? '';
            // コミックのジャンルID（101299）で始まるか、または空の場合は含める（APIで既にフィルタリングされている可能性がある）
            if (!empty($booksGenreId) && strpos($booksGenreId, '101299') !== 0) {
                continue;
            }
            
            // タイトルと著者を取得
            $title = $bookItem['title'] ?? '';
            $author = $bookItem['author'] ?? '';
            $isbn = $bookItem['isbn'] ?? '';
            
            // 表紙画像を取得（大→中→小の順で優先）
            $coverImage = '';
            if (!empty($bookItem['largeImageUrl'])) {
                $coverImage = $bookItem['largeImageUrl'];
            } elseif (!empty($bookItem['mediumImageUrl'])) {
                $coverImage = $bookItem['mediumImageUrl'];
            } elseif (!empty($bookItem['smallImageUrl'])) {
                $coverImage = $bookItem['smallImageUrl'];
            }
            
            // manga_idはISBNを使用（ISBNがない場合はタイトルと著者から生成）
            $mangaId = !empty($isbn) ? $isbn : md5($title . $author);
            
            if (!empty($title) && !empty($mangaId)) {
                $results[] = [
                    'manga_id' => $mangaId,
                    'title' => $title,
                    'author' => $author,
                    'cover_image' => $coverImage
                ];
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
        
        $bookItem = $data['Items'][0]['Item'] ?? [];
        
        if (empty($bookItem)) {
            return null;
        }
        
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
            'isbn' => $bookItem['isbn'] ?? $isbn,
            'title' => $bookItem['title'] ?? '',
            'author' => $bookItem['author'] ?? '',
            'cover_image' => $coverImage
        ];
    }
}

