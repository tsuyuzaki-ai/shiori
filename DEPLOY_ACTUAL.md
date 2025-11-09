# 実際のデプロイ手順（具体的なコマンド）

## サーバー情報
- **SSH接続**: `ssh root@210.131.209.239`
- **Rootパスワード**: `E4JHyfxRdXMD`
- **データベースユーザー**: `tsuyuzaki`
- **データベースパスワード**: `Bz9XsWGk`
- **ホスト名**: `x210-131-209-239.static.xvps.ne.jp`

---

## ステップ1: サーバーにSSH接続

**ローカルのターミナルで実行：**

```bash
ssh root@210.131.209.239
```

パスワードを求められたら: `E4JHyfxRdXMD`

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

## ステップ3: データベースの確認・作成

**まず、既存のデータベースを確認：**

```bash
mysql -u root -p
```

パスワードを求められたら、MySQLのrootパスワードを入力（通常はサーバーのrootパスワードと同じか、別途設定されている場合があります）

MySQLに接続後：

```sql
-- 既存のデータベースを確認
SHOW DATABASES;

-- データベースが存在しない場合、作成
CREATE DATABASE IF NOT EXISTS shiori_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- ユーザーに権限を付与（既にユーザーが存在する場合）
GRANT ALL PRIVILEGES ON shiori_db.* TO 'tsuyuzaki'@'localhost';

-- 権限を反映
FLUSH PRIVILEGES;

-- 確認
SHOW DATABASES;
EXIT;
```

**または、SQLファイルを使用する場合：**

```bash
# SQLファイルを編集
nano mysql/init/00_create_database.sql
```

以下のように編集：
- `shiori_user` → `tsuyuzaki`
- `shiori_password` → `Bz9XsWGk`
- `shiori_db` → 使用するデータベース名（既存のDBを使う場合はその名前）

保存後：

```bash
mysql -u root -p < mysql/init/00_create_database.sql
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

**nanoエディタで以下を記入：**

```
APP_ENV=production
DB_HOST=localhost
DB_DATABASE=shiori_db
DB_USERNAME=tsuyuzaki
DB_PASSWORD=Bz9XsWGk
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
mysql -u tsuyuzaki -p shiori_db < mysql/init/01_create_tables.sql
```

パスワードを求められたら: `Bz9XsWGk`

**または、既存のデータベースを使用する場合：**

```bash
# 既存のデータベース名に置き換えて実行
mysql -u tsuyuzaki -p 既存のDB名 < mysql/init/01_create_tables.sql
```

---

## ステップ7: Webサーバーの設定

**まず、Webサーバーのドキュメントルートを確認：**

```bash
# Apacheの場合
ls -la /var/www/html/

# または
ls -la /var/www/

# Nginxの場合
ls -la /usr/share/nginx/html/
```

**シンボリックリンクを作成：**

```bash
# 現在のディレクトリを確認
pwd

# シンボリックリンクを作成（Apacheの場合）
ln -s $(pwd)/php/public /var/www/html/shiori

# または、Nginxの場合
# ln -s $(pwd)/php/public /usr/share/nginx/html/shiori
```

**Webサーバーを再起動：**

```bash
# Apacheの場合
systemctl restart apache2
# または
service apache2 restart

# Nginxの場合
systemctl restart nginx
# または
service nginx restart
```

---

## ステップ8: 動作確認

**ブラウザでアクセス：**

```
http://app-portfolio.xvps.jp/shiori
```

または

```
http://210.131.209.239/shiori
```

ログインページが表示されれば成功です！

---

## トラブルシューティング

### データベース接続エラーの場合

```bash
# データベースが作成されているか確認
mysql -u root -p -e "SHOW DATABASES;"

# ユーザーの権限を確認
mysql -u root -p -e "SHOW GRANTS FOR 'tsuyuzaki'@'localhost';"

# 接続テスト
mysql -u tsuyuzaki -p -e "USE shiori_db; SHOW TABLES;"
```

パスワード: `Bz9XsWGk`

### ファイルが見つからないエラーの場合

```bash
# パーミッションを確認
ls -la php/public/

# パーミッションを修正
chmod -R 755 php/public
find php/public -type f -name "*.php" -exec chmod 644 {} \;
```

### PHPエラーの場合

```bash
# エラーログを確認
tail -f /var/log/apache2/error.log
# または
tail -f /var/log/nginx/error.log
```

### Webサーバーが起動していない場合

```bash
# Apacheの場合
systemctl status apache2
systemctl start apache2

# Nginxの場合
systemctl status nginx
systemctl start nginx
```

---

## 2回目以降のデプロイ（更新時）

```bash
# 1. サーバーにSSH接続
ssh root@210.131.209.239

# 2. プロジェクトディレクトリに移動
cd ~/shiori

# 3. デプロイスクリプトを実行
./deploy.sh 更新
```

これで完了です！

