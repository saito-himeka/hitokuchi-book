FROM php:8.1-apache

# 1. 必要なライブラリをインストール
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

# 4. ファイルをコピー
WORKDIR /var/www/html
COPY . .

# 5. 権限設定（先にやっておく）
RUN chown -R www-data:www-data /var/www/html/src/storage /var/www/html/src/bootstrap/cache

# 6. ライブラリをインストール（スクリプトなしで確実に）
RUN cd src && composer install --no-dev --no-scripts --optimize-autoloader

# 7. 実行コマンド（ここが最重要！）
CMD sh -c "sleep 10 && php /var/www/html/src/artisan migrate:fresh --seed --force && apache2-foreground"

EXPOSE 80