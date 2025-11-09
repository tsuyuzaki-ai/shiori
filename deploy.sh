#!/bin/bash

# Shiori デプロイスクリプト
# 使用方法: ./deploy.sh [初回|更新]

set -e  # エラーが発生したら停止

# 色の定義
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# ログ出力関数
log_info() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

log_warn() {
    echo -e "${YELLOW}[WARN]${NC} $1"
}

log_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# デプロイタイプの確認
DEPLOY_TYPE=${1:-更新}

if [ "$DEPLOY_TYPE" = "初回" ]; then
    log_info "初回デプロイを開始します"
    IS_FIRST_DEPLOY=true
else
    log_info "更新デプロイを開始します"
    IS_FIRST_DEPLOY=false
fi

# プロジェクトディレクトリの確認
if [ ! -f "composer.json" ] && [ ! -f "php/composer.json" ]; then
    log_error "プロジェクトディレクトリが見つかりません"
    exit 1
fi

# Gitの最新化
log_info "Gitから最新のコードを取得します..."
git pull origin main

if [ $? -ne 0 ]; then
    log_error "Git pullに失敗しました"
    exit 1
fi

# Composerの依存関係をインストール
log_info "Composerの依存関係をインストールします..."
cd php
composer install --no-dev --optimize-autoloader

if [ $? -ne 0 ]; then
    log_error "Composer installに失敗しました"
    exit 1
fi

cd ..

# 初回デプロイの場合の追加処理
if [ "$IS_FIRST_DEPLOY" = true ]; then
    log_warn "初回デプロイの追加設定が必要です:"
    echo "  1. php/.env ファイルを作成して設定してください"
    echo "  2. データベースを初期化してください:"
    echo "     mysql -u DBユーザー名 -p データベース名 < mysql/init/01_create_tables.sql"
    echo "  3. パーミッションを設定してください:"
    echo "     chmod -R 755 php/public"
    echo "     chmod -R 644 php/public/*.php"
fi

# パーミッションの確認
log_info "パーミッションを確認します..."
if [ -d "php/public" ]; then
    chmod -R 755 php/public
    find php/public -type f -name "*.php" -exec chmod 644 {} \;
    log_info "パーミッションを設定しました"
fi

log_info "デプロイが完了しました！"
log_info "動作確認: http://app-portfolio.xvps.jp/shiori"

