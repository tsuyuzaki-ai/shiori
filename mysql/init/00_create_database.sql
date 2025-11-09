-- データベースとユーザーの作成スクリプト
-- サーバー上でMySQLのrootユーザーで実行してください

-- データベースの作成（存在しない場合）
CREATE DATABASE IF NOT EXISTS shiori_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- ユーザーの作成（存在しない場合）
-- 注意: 'shiori_user' と 'shiori_password' は実際の値に置き換えてください
CREATE USER IF NOT EXISTS 'shiori_user'@'localhost' IDENTIFIED BY 'shiori_password';

-- 権限の付与
GRANT ALL PRIVILEGES ON shiori_db.* TO 'shiori_user'@'localhost';

-- 権限の反映
FLUSH PRIVILEGES;

-- 確認用
SHOW DATABASES;
SELECT User, Host FROM mysql.user WHERE User = 'shiori_user';

