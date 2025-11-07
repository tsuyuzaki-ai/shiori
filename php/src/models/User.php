<?php

class User
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // ユーザー登録
    public function create($username, $password)
    {
        // 同じユーザー名が存在するかチェック
        // COUNT(*)→行数を数える
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
        $stmt->execute([$username]);
        // 結果は1行1列のためfetchColumnの取り回しが一番良い
        if ($stmt->fetchColumn() > 0) {
            throw new Exception("このユーザー名は既に使用されています");
        }

        // パスワードをハッシュ化
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // ユーザーを登録
        $stmt = $this->pdo->prepare("INSERT INTO users (username, password, created_at, updated_at) VALUES (?, ?, NOW(), NOW())");
        return $stmt->execute([$username, $hashedPassword]);
    }

    // ユーザー認証
    public function authenticate($username, $password)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        // password_verify→ハッシュ化されていてもわかる
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    // ユーザー取得
    public function findById($userId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }
}

