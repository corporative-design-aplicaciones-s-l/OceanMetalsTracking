FROM php:8.2-fpm

# Instala dependencias del sistema
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    curl \
    git \
    mariadb-client \
    && docker-php-ext-install zip pdo_mysql

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configura directorio de trabajo
WORKDIR /var/www/html

# Copia archivos del proyecto
COPY . .

# Instala dependencias del proyecto
RUN composer install --no-dev --optimize-autoloader
