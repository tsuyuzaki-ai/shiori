# Shiori - マンガ巻数管理アプリ

漫画を何巻まで読んだか管理するWebアプリケーションです。

## 技術スタック

- PHP 8.2
- MySQL 8.0
- Docker / Docker Compose
- openBD API

## セットアップ

### 1. 依存関係のインストール

```bash
cd php
composer install
```

### 2. Dockerコンテナの起動

```bash
docker-compose up -d
```

### 3. データベースの初期化

データベーステーブルは自動的に作成されます（`mysql/init/01_create_tables.sql`）。

### 4. アプリケーションへのアクセス

- ローカル開発: http://localhost:8000/shiori/
- 本番環境: /shiori/public 配下で動作

## ディレクトリ構成

```
shiori/
├── docker-compose.yml          # Docker Compose設定
├── php/                        # PHPアプリケーション
│   ├── Dockerfile              # PHPコンテナのDockerfile
│   ├── composer.json           # PHP依存関係
│   ├── public/                 # 公開ディレクトリ
│   │   ├── index.php           # エントリーポイント
│   │   └── .htaccess           # Apache設定
│   └── src/                    # ソースコード
│       ├── bootstrap.php       # アプリケーション初期化
│       ├── config/             # 設定ファイル
│       │   ├── database.php    # DB設定
│       │   └── app.php         # アプリ設定
│       ├── models/             # モデルクラス
│       │   ├── User.php        # ユーザーモデル
│       │   └── Manga.php       # 漫画モデル
│       ├── controllers/        # コントローラー（今後追加）
│       ├── views/              # ビューファイル（今後追加）
│       └── services/           # サービスクラス
│           └── OpenBDService.php  # openBD APIサービス
├── mysql/                      # MySQL関連
│   └── init/                   # 初期化SQL
│       └── 01_create_tables.sql
└── README.md                   # このファイル
```

## データベース構造

### users テーブル
- user_id (INT, PRIMARY KEY)
- username (VARCHAR, UNIQUE)
- password (VARCHAR)
- created_at (TIMESTAMP)
- updated_at (TIMESTAMP)

### mangas テーブル
- id (INT, PRIMARY KEY)
- user_id (INT, FOREIGN KEY)
- manga_id (VARCHAR)
- manga_name (VARCHAR)
- author_name (VARCHAR)
- volume (INT)
- is_completed (BOOLEAN)
- created_at (TIMESTAMP)
- updated_at (TIMESTAMP)

## 機能

- ユーザー登録・認証
- 漫画の一覧表示（更新日時順、読了は最下部）
- 巻数の増減
- 読了フラグの切り替え
- openBD APIを使用した漫画検索
- 漫画のCRUD操作

## Docker コマンド

```bash
# コンテナ起動
docker-compose up -d

# コンテナ停止
docker-compose down

# ログ確認
docker-compose logs -f

# コンテナ再起動
docker-compose restart
```

