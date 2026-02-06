FROM php:8.1-apache

# 1. 必要なツールとNode.jsをインストール
# curl を先に入れてから、Node.jsのリポジトリを追加します
RUN apt-get update && apt-get install -y \
    curl \
    libzip-dev \
    unzip \
    libpq-dev \
    && curl -sL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && docker-php-ext-install pdo_pgsql zip

# 2. Apache設定
ENV APACHE_DOCUMENT_ROOT /var/www/html/src/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN a2enmod rewrite

# 3. Composerインストール
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

# 4. 権限設定
RUN chown -R www-data:www-data /var/www/html/src/storage /var/www/html/src/bootstrap/cache

# 5. npmライブラリのインストールとビルド
# ViteによるCSS/JSのビルドを行います
RUN cd src && npm install && npm run build

# 6. PHPライブラリインストール
RUN cd src && composer install --no-dev --no-scripts --optimize-autoloader

# 7. 実行コマンド
CMD ["sh", "-c", "cd /var/www/html/src && php artisan migrate:fresh --seed --force && apache2-foreground"]

EXPOSE 80