-- データベースとユーザーの作成スクリプト（実際のサーバー用）
-- サーバー上でMySQLのrootユーザーで実行してください

-- データベースの作成（存在しない場合）
CREATE DATABASE IF NOT EXISTS shiori_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- 既存のユーザーに権限を付与（ユーザーは既に存在するため）
GRANT ALL PRIVILEGES ON shiori_db.* TO 'tsuyuzaki'@'localhost';

-- 権限の反映
FLUSH PRIVILEGES;

-- 確認用
SHOW DATABASES;
SELECT User, Host FROM mysql.user WHERE User = 'tsuyuzaki';

