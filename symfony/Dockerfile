FROM php:8.2-apache

# Устанавливаем зависимости
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    libpng-dev \
    libzip-dev \
    libpq-dev \
    && rm -rf /var/lib/apt/lists/*

# Устанавливаем расширения PHP
RUN docker-php-ext-install pdo pdo_pgsql zip gd

# Включаем модуль rewrite для Apache
RUN a2enmod rewrite

# Устанавливаем рабочую директорию
WORKDIR /var/www/html

# Копируем исходный код
COPY . .

# Устанавливаем окружение
ENV APP_ENV=dev XDEBUG_MODE=off

# Копируем Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Устанавливаем зависимости Composer
RUN COMPOSER_ALLOW_SUPERUSER=1 composer install --no-cache --prefer-dist --no-dev --no-autoloader --no-scripts --no-progress

# Настраиваем права доступа
RUN chown -R www-data:www-data /var/www/html

# Открываем порт 80
EXPOSE 80

# Настраиваем Apache для работы с Symfony
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# Запускаем Apache
CMD ["apache2-foreground"]
