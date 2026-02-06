FROM php:8.1-apache

# PostgreSQL用のシステムライブラリをインストール
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    libpq-dev \
    && docker-php-ext-install pdo_pgsql zip

# 設定ファイルをコピー
COPY docker/php/php.ini /usr/local/etc/php/

# Apache設定（ドキュメントルートをLaravelのpublicへ）
ENV APACHE_DOCUMENT_ROOT /var/www/html/src/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN a2enmod rewrite

# Composerインストール
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# プロジェクトファイルをコピー
WORKDIR /var/www/html
COPY . .

# 権限の設定
RUN chown -R www-data:www-data /var/www/html/src/storage /var/www/html/src/bootstrap/cache

# ★重要★ ライブラリインストール時に、エラーの原因となる自動実行スクリプトを一旦「無視」させる
RUN cd src && composer install --no-dev --no-scripts --optimize-autoloader

EXPOSE 80