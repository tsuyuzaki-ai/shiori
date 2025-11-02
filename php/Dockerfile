FROM php:8.2-apache

# PDO MySQL ドライバのインストール
RUN docker-php-ext-install pdo pdo_mysql

# Apache のドキュメントルートを /var/www/html/public に設定
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# mod_rewrite を有効化
RUN a2enmod rewrite

# 作業ディレクトリを設定
WORKDIR /var/www/html

