# --- ステージ1: CSS/JSをビルドする専用の部屋 ---
FROM node:18 AS build-stage
WORKDIR /app
COPY . .
# package.jsonがあるディレクトリ（src）に移動してビルド
RUN cd src && npm install && npm run prod

# --- ステージ2: 本番用のPHPサーバーの部屋 ---
FROM php:8.1-apache

# 1. 必要なツールをインストール（Node.jsは不要になったので削除）
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    libpq-dev \
    && docker-php-ext-install pdo_pgsql zip

# 2. Apache設定
ENV APACHE_DOCUMENT_ROOT /var/www/html/src/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN a2enmod rewrite

# 3. Composerインストール
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
# ステージ1でビルドした成果物（CSSなど）を含むすべてのファイルをコピー
COPY --from=build-stage /app .

# 4. 権限設定
RUN chown -R www-data:www-data /var/www/html/src/storage /var/www/html/src/bootstrap/cache

# 5. PHPライブラリインストール
RUN cd src && composer install --no-dev --no-scripts --optimize-autoloader

# 6. 実行コマンド
CMD ["sh", "-c", "cd /var/www/html/src && php artisan storage:link && php artisan migrate --force && apache2-foreground"]

EXPOSE 80