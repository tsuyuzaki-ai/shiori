# サーバーデプロイ手順

## 初回デプロイ

### 1. サーバーにSSH接続
```bash
ssh ユーザー名@app-portfolio.xvps.jp
```

### 2. プロジェクトのクローン（初回のみ）
```bash
cd /path/to/webroot  # 適切なディレクトリに移動
git clone https://github.com/tsuyuzaki-ai/shiori.git
cd shiori
```

### 3. 最新のコードを取得（2回目以降はこれだけ）
```bash
cd /path/to/shiori
git pull origin main
```

### 4. Composerの依存関係をインストール
```bash
cd php
composer install --no-dev --optimize-autoloader
```

### 5. 環境変数ファイルの設定
```bash
cd php
# .envファイルを作成（存在しない場合）
touch .env

# .envファイルを編集して本番環境の設定を記入
nano .env
```

`php/.env`ファイルの設定例：
```
APP_ENV=production
DB_HOST=localhost
DB_DATABASE=実際のデータベース名
DB_USERNAME=実際のDBユーザー名
DB_PASSWORD=実際のDBパスワード
BASE_URL=http://app-portfolio.xvps.jp/shiori
```

### 5.5. データベースの事前準備（初回のみ）

サーバー側でデータベースとユーザーを作成する必要があります。

#### オプションA: SQLファイルを使用（推奨）

```bash
# MySQLのrootユーザーで実行
# 注意: 00_create_database.sql内のユーザー名とパスワードを実際の値に置き換えてください
mysql -u root -p < mysql/init/00_create_database.sql
```

#### オプションB: 手動で作成

```bash
mysql -u root -p
```

```sql
CREATE DATABASE IF NOT EXISTS shiori_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS 'shiori_user'@'localhost' IDENTIFIED BY '実際のパスワード';
GRANT ALL PRIVILEGES ON shiori_db.* TO 'shiori_user'@'localhost';
FLUSH PRIVILEGES;
```

### 6. データベースの初期化
```bash
# MySQLに接続して初期化SQLを実行
mysql -u DBユーザー名 -p データベース名 < mysql/init/01_create_tables.sql
```

または、MySQLに直接接続して実行：
```bash
mysql -u DBユーザー名 -p
```
```sql
USE データベース名;
SOURCE mysql/init/01_create_tables.sql;
```

### 7. ファイルのパーミッション設定
```bash
# publicディレクトリのパーミッション設定
chmod -R 755 php/public
chmod -R 644 php/public/*.php
```

### 8. Webサーバーの設定確認

#### Apacheの場合
- `/shiori/public` がドキュメントルートとして設定されているか確認
- または、シンボリックリンクを作成：
```bash
ln -s /path/to/shiori/php/public /path/to/webroot/shiori
```

#### .htaccessの確認
`php/public/.htaccess` が正しく配置されているか確認

### 9. 動作確認
ブラウザで以下にアクセス：
- http://app-portfolio.xvps.jp/shiori

## 2回目以降のデプロイ（更新時）

```bash
# 1. サーバーにSSH接続
ssh ユーザー名@app-portfolio.xvps.jp

# 2. プロジェクトディレクトリに移動
cd /path/to/shiori

# 3. 最新のコードを取得
git pull origin main

# 4. Composerの依存関係を更新（必要に応じて）
cd php
composer install --no-dev --optimize-autoloader

# 5. 動作確認
# http://app-portfolio.xvps.jp/shiori にアクセス
```

## デプロイスクリプトの使用

プロジェクトルートに `deploy.sh` スクリプトを用意しています。

### 使用方法

```bash
# サーバーにSSH接続後、プロジェクトディレクトリで実行
cd /path/to/shiori

# 初回デプロイ
./deploy.sh 初回

# 2回目以降の更新
./deploy.sh 更新
# または
./deploy.sh
```

スクリプトは以下の処理を自動実行します：
1. Gitから最新のコードを取得
2. Composerの依存関係をインストール
3. パーミッションの設定

## トラブルシューティング

### データベース接続エラー
- `php/.env`ファイルのDB設定を確認
- データベースが作成されているか確認
- ユーザーに適切な権限が付与されているか確認

### ファイルが見つからないエラー
- パーミッションを確認（755 for directories, 644 for files）
- `.htaccess`が正しく配置されているか確認
- Webサーバーのドキュメントルート設定を確認

### Composerエラー
- PHPのバージョンを確認（PHP 8.2以上が必要）
- `composer install`を実行

### セッションエラー
- `php/public`ディレクトリの書き込み権限を確認
- PHPのセッション設定を確認

