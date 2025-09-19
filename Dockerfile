FROM php:8.2-fpm

# Instala dependências do sistema
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip nodejs npm \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Instala Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copia só os arquivos necessários para instalar dependências
COPY composer.json composer.lock ./
RUN composer install --no-scripts --no-autoloader

# Copia o resto do projeto
COPY . .

# Finaliza autoload e otimiza
RUN composer dump-autoload
