# デプロイ実行手順（コピー&ペースト用）

## 【重要】事前に確認すること

以下の情報を準備してください：
- SSH接続のユーザー名
- データベース名（例: `shiori_db`）
- データベースユーザー名（例: `shiori_user`）
- データベースパスワード

---

## ステップ1: サーバーにSSH接続

**ローカルのターミナルで実行：**

```bash
ssh ユーザー名@app-portfolio.xvps.jp
```

※ `ユーザー名` を実際のユーザー名に置き換えてください

---

## ステップ2: プロジェクトをクローン

**サーバー上で実行：**

```bash
# ホームディレクトリに移動
cd ~

# プロジェクトをクローン
git clone https://github.com/tsuyuzaki-ai/shiori.git

# プロジェクトディレクトリに移動
cd shiori
```

---

## ステップ3: データベースの作成（初回のみ）

**サーバー上で実行：**

### オプションA: SQLファイルを編集して実行（推奨）

```bash
# SQLファイルを編集（ユーザー名とパスワードを実際の値に変更）
nano mysql/init/00_create_database.sql
```

編集内容：
- `shiori_user` → 実際のデータベースユーザー名
- `shiori_password` → 実際のデータベースパスワード
- `shiori_db` → 実際のデータベース名（変更する場合）

編集後、保存（Ctrl+O → Enter → Ctrl+X）

```bash
# MySQLのrootユーザーで実行
mysql -u root -p < mysql/init/00_create_database.sql
```

### オプションB: 手動でデータベースを作成

```bash
mysql -u root -p
```

MySQLに接続後、以下を実行（実際の値に置き換えてください）：

```sql
CREATE DATABASE IF NOT EXISTS shiori_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS 'shiori_user'@'localhost' IDENTIFIED BY '実際のパスワード';
GRANT ALL PRIVILEGES ON shiori_db.* TO 'shiori_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

---

## ステップ4: デプロイスクリプトを実行

**サーバー上で実行：**

```bash
# 実行権限を付与
chmod +x deploy.sh

# デプロイスクリプトを実行
./deploy.sh 初回
```

---

## ステップ5: 環境変数ファイル（.env）の設定

**サーバー上で実行：**

```bash
# phpディレクトリに移動
cd php

# .envファイルを作成
touch .env

# .envファイルを編集
nano .env
```

**nanoエディタで以下を記入（実際の値に置き換えてください）：**

```
APP_ENV=production
DB_HOST=localhost
DB_DATABASE=shiori_db
DB_USERNAME=shiori_user
DB_PASSWORD=実際のパスワード
BASE_URL=http://app-portfolio.xvps.jp/shiori
```

保存方法：
- Ctrl+O（保存）
- Enter（確認）
- Ctrl+X（終了）

---

## ステップ6: データベーステーブルの作成

**サーバー上で実行：**

```bash
# プロジェクトルートに戻る
cd ..

# データベーステーブルを作成
mysql -u shiori_user -p shiori_db < mysql/init/01_create_tables.sql
```

※ `shiori_user` と `shiori_db` を実際の値に置き換えてください
※ パスワードを求められたら、データベースパスワードを入力

---

## ステップ7: Webサーバーの設定

### オプションA: シンボリックリンクを作成（推奨）

**サーバー上で実行：**

```bash
# 現在のディレクトリを確認
pwd

# シンボリックリンクを作成
# 例: /var/www/html/shiori にリンクを作成する場合
sudo ln -s $(pwd)/php/public /var/www/html/shiori
```

※ `/var/www/html` がWebサーバーのドキュメントルートの場合
※ 実際のドキュメントルートのパスに合わせて調整してください

### オプションB: 既に設定済みの場合

サーバー管理者に確認してください。

---

## ステップ8: 動作確認

**ブラウザでアクセス：**

```
http://app-portfolio.xvps.jp/shiori
```

ログインページが表示されれば成功です！

---

## トラブルシューティング

### データベース接続エラーの場合

```bash
# データベースが作成されているか確認
mysql -u root -p -e "SHOW DATABASES;"

# ユーザーが作成されているか確認
mysql -u root -p -e "SELECT User, Host FROM mysql.user WHERE User = 'shiori_user';"

# 接続テスト
mysql -u shiori_user -p shiori_db -e "SHOW TABLES;"
```

### ファイルが見つからないエラーの場合

```bash
# パーミッションを確認
ls -la php/public/

# パーミッションを修正
chmod -R 755 php/public
chmod -R 644 php/public/*.php
```

### PHPエラーの場合

```bash
# エラーログを確認
tail -f /var/log/apache2/error.log
```

---

## 2回目以降のデプロイ（更新時）

```bash
# 1. サーバーにSSH接続
ssh ユーザー名@app-portfolio.xvps.jp

# 2. プロジェクトディレクトリに移動
cd ~/shiori

# 3. デプロイスクリプトを実行
./deploy.sh 更新
```

これで完了です！

