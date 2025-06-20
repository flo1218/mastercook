# Utiliser une image PHP 8.3 FPM basée sur Alpine
FROM php:8.4-fpm-alpine

RUN apk update && apk upgrade --no-cache

# Installer les dépendances système et Node.js
RUN apk add --no-cache \
    git \
    unzip \
    libpq \
    libzip-dev \
    icu-libs \
    icu-dev \
    libxml2 \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    autoconf \
    make \
    gcc \
    g++ \
    musl-dev \
    linux-headers \
    $PHPIZE_DEPS \
    nodejs \
    npm

# Installer les extensions PHP une par une pour garder les libs nécessaires
RUN docker-php-ext-configure gd --with-freetype --with-jpeg

RUN docker-php-ext-install gd

RUN docker-php-ext-install pdo pdo_mysql intl

# Garder libzip-dev pour zip.so (ne pas le supprimer avant composer install)
RUN docker-php-ext-install zip

# Installer Xdebug
RUN pecl install xdebug && docker-php-ext-enable xdebug

# Configurer Xdebug
COPY ./docker/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/symrecipe

COPY composer.json  ./
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-progress

COPY . .

EXPOSE 9000

# Nettoyage des libs de build APRÈS composer install et extensions PHP
#RUN apk del icu-dev libzip-dev libpng-dev libjpeg-turbo-dev freetype-dev autoconf make gcc g++ musl-dev linux-headers

# Augmenter la mémoire disponible pour PHP CLI
ENV PHP_MEMORY_LIMIT=512M
env NODE_ENV=development

CMD ["php-fpm"]
