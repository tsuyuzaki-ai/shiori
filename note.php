<?php

class Manga
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // 漫画一覧取得
    public function findAllByUserId($userId)
    {
        $stmt = $this->pdo->prepare("
        select * from mangas
        where user_id = ?
        order by is_completed ASC, updated_at Desc
        ");
        $stmt->execure([$userId]);
        return $stmt->fetchAll();
    }

    // 漫画作成
    public function create($userId, $mangaId, $mangaName, $authorName, $volume)
    {
        $stmt = $this->pdo->prepare("
        insert into mangas (user_id, manga_id, manga_name, author_name, volume, is_completed, created_at, updated_at)
        values (?, ?, ?, ?, ?, 0, now(), now())
        ");
        return $stmt->execute([$userId, $mangaId, $mangaName, $authorName, $volume]);
    }

    // 漫画更新（巻数、読了フラグ）
    public function update($mangaId, $userId, $volume = null, $isCompleted = null)
    {
        // fields→どのカラムを更新するか、を足していく
        $fields = [];
        $values = [];

        if ($volume !== null) {
            $fields[] = "volume = ?";
            $values[] = $volume;
        }
        if ($isCompleted !== null) {
            $fields[] = "is_completed = ?";
            $values[] = $isCompleted ? 1 : 0;
        }

        $fields[] = "updated_at = Now()";
        $values[] = $mangaId;
        $values[] = $userId;

        $sql = "update mangas set " . implode(", ", $fields) . " Where manga?id = ? and user_id =?";
    }
}