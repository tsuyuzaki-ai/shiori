<?php

class Manga
{
    private $pdo;

    // newで自動的に実行
    public function __construct($pdo)
    {
        // $thisはMangaクラス $pdo(DB接続を使えるよう代入)
        // $mangaModel = new Manga($pdo);
        $this->pdo = $pdo;
    }

    // 漫画一覧取得（更新日時新しい順、読了は最後）
    public function findAllByUserId($userId)
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM mangas 
            WHERE user_id = ? 
            ORDER BY is_completed ASC, updated_at DESC
        ");
        // ?プレスホルダーに＄userIdを入れて上記を実行
        $stmt->execute([$userId]);
        // 実行した結果全てを配列で返す
        return $stmt->fetchAll();
    }



    // 漫画作成
    public function create($userId, $mangaId, $mangaName, $authorName, $volume, $coverImage = null)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO mangas (user_id, manga_id, manga_name, author_name, cover_image, volume, is_completed, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, 0, NOW(), NOW())
        ");
        // プレスホルダーにこの配列を入れ 上記を実行
        return $stmt->execute([$userId, $mangaId, $mangaName, $authorName, $coverImage, $volume]);
    }



    // 漫画更新（巻数、読了フラグ）
    // = →初期値の設定
    public function update($mangaId, $userId, $volume = null, $isCompleted = null)
    {
        // どっちが更新されても、両方更新されても代入できるように
        // fields→どのカラムを更新するか、を足していく
        $fields = [];
        // values→どの値に更新するか、を足していく
        $values = [];

        if ($volume !== null) {
            $fields[] = "volume = ?";
            $values[] = $volume;
        }

        if ($isCompleted !== null) {
            $fields[] = "is_completed = ?";
            $values[] = $isCompleted ? 1 : 0;
        }

        $fields[] = "updated_at = NOW()";
        $values[] = $mangaId;
        $values[] = $userId;

        $sql = "UPDATE mangas SET " . implode(", ", $fields) . " WHERE manga_id = ? AND user_id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($values);
    }

    // 漫画削除
    public function delete($mangaId, $userId)
    {
        $stmt = $this->pdo->prepare("DELETE FROM mangas WHERE manga_id = ? AND user_id = ?");
        return $stmt->execute([$mangaId, $userId]);
    }

    // 漫画検索（自分のリスト内）
    public function searchByUserId($userId, $keyword)
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM mangas 
            WHERE user_id = ? 
            AND (manga_name LIKE ? OR author_name LIKE ?)
            ORDER BY is_completed ASC, updated_at DESC
        ");
        // 部分一致
        $searchTerm = "%{$keyword}%";
        $stmt->execute([$userId, $searchTerm, $searchTerm]);
        return $stmt->fetchAll();
    }
}

