<?php
// エントリーポイント: ログインページ
// このファイルはURLから直接アクセスされます: http://localhost:8000/shiori/login.php

require_once __DIR__ . '/../src/bootstrap.php';

// ログインページはデータ取得なし（認証処理は auth.php で行う）
// ビューを表示するだけ
require_once __DIR__ . '/../src/views/login.php';

