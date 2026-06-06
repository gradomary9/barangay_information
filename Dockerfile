FROM php:8.4-apache

RUN apt-get update && apt-get install -y \
    git unzip zip curl \
    libzip-dev libpng-dev libonig-dev libxml2-dev \
    libsqlite3-dev sqlite3 \
    nodejs npm \
    && docker-php-ext-install pdo pdo_mysql pdo_sqlite mbstring zip exif pcntl bcmath gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN npm install && npm run build

RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache
RUN touch database/database.sqlite

RUN chown -R www-data:www-data storage bootstrap/cache database

RUN a2enmod rewrite

COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf

EXPOSE 80

CMD php artisan migrate --force && php artisan db:seed --force && php artisan storage:link && apache2-foreground
