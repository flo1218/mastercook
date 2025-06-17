# Utiliser une image PHP avec les extensions nécessaires
FROM php:8.4-fpm

RUN usermod -u 1000 www-data

# Installer les dépendances système
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    unzip \
    libonig-dev \
    libzip-dev \
    libicu-dev \
    libxml2-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    weasyprint \
    && docker-php-ext-install pdo pdo_mysql intl zip gd

# Installer Xdebug
RUN pecl install xdebug && docker-php-ext-enable xdebug

# Configurer Xdebug
COPY ./docker/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Installer Node.js et npm (version 18 LTS)
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Définir le répertoire de travail
WORKDIR /var/www/symrecipe

# Copier les fichiers de l'application
COPY . .

ENV XDEBUG_MODE=off
# Installer les dépendances PHP
RUN composer install --optimize-autoloader

ENV XDEBUG_MODE=debug

# Exposer le port 9000 pour PHP-FPM
EXPOSE 9000

# Commande par défaut
CMD ["php-fpm"]
