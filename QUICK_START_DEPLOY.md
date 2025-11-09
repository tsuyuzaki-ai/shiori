# クイックスタート：デプロイ手順

## ステップ1: サーバー情報の確認

以下の情報を準備してください：

1. **SSH接続情報**
   - ユーザー名: `?`
   - ホスト: `app-portfolio.xvps.jp`

2. **サーバー上のプロジェクトパス**
   - 例: `/home/username/shiori` または `/var/www/shiori`

3. **データベース情報**
   - データベース名: `?`
   - ユーザー名: `?`
   - パスワード: `?`

## ステップ2: 初回デプロイの実行

### 2-1. サーバーにSSH接続

```bash
ssh ユーザー名@app-portfolio.xvps.jp
```

### 2-2. プロジェクトをクローン（初回のみ）

```bash
# 適切なディレクトリに移動（例：ホームディレクトリ）
cd ~

# プロジェクトをクローン
git clone https://github.com/tsuyuzaki-ai/shiori.git
cd shiori
```

### 2-3. デプロイスクリプトを実行

```bash
# 実行権限を付与（初回のみ）
chmod +x deploy.sh

# 初回デプロイを実行
./deploy.sh 初回
```

### 2-4. 環境変数ファイルの設定

```bash
cd php
cp .env.example .env  # .env.exampleが存在する場合
# または
touch .env

# .envファイルを編集
nano .env
```

`.env`ファイルに以下を記入：
```
APP_ENV=production
DB_HOST=localhost
DB_DATABASE=実際のデータベース名
DB_USERNAME=実際のDBユーザー名
DB_PASSWORD=実際のDBパスワード
BASE_URL=http://app-portfolio.xvps.jp/shiori
```

### 2-5. データベースの初期化

```bash
# プロジェクトルートに戻る
cd ..

# データベースを初期化
mysql -u DBユーザー名 -p データベース名 < mysql/init/01_create_tables.sql
```

パスワードを求められたら、データベースパスワードを入力してください。

### 2-6. Webサーバーの設定

#### オプションA: シンボリックリンクを作成（推奨）

```bash
# Webサーバーのドキュメントルートにシンボリックリンクを作成
# 例：/var/www/html/shiori にリンクを作成する場合
sudo ln -s $(pwd)/php/public /var/www/html/shiori
```

#### オプションB: Apacheの設定を確認

Apacheの設定で `/shiori` が `php/public` ディレクトリを指すように設定されているか確認してください。

### 2-7. 動作確認

ブラウザで以下にアクセス：
- http://app-portfolio.xvps.jp/shiori

ログインページが表示されれば成功です！

## ステップ3: 2回目以降のデプロイ（更新時）

```bash
# 1. サーバーにSSH接続
ssh ユーザー名@app-portfolio.xvps.jp

# 2. プロジェクトディレクトリに移動
cd /path/to/shiori

# 3. デプロイスクリプトを実行
./deploy.sh 更新
```

これで完了です！

## トラブル時の確認コマンド

### データベース接続の確認
```bash
mysql -u DBユーザー名 -p -e "SHOW DATABASES;"
```

### ファイルパーミッションの確認
```bash
ls -la php/public/
```

### PHPエラーログの確認
```bash
tail -f /var/log/apache2/error.log
```

### Gitの状態確認
```bash
git status
git log --oneline -5
```

