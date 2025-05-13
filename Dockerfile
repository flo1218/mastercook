# Utiliser une image PHP avec les extensions nécessaires
FROM php:8.3-fpm

RUN usermod -u 1000 www-data

# Installer les dépendances système
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libonig-dev \
    libzip-dev \
    libicu-dev \
    libxml2-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-install pdo pdo_mysql intl zip gd

# Installer Xdebug
RUN pecl install xdebug && docker-php-ext-enable xdebug

# Configurer Xdebug
COPY ./docker/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www/symrecipe

# Copier les fichiers de l'application
COPY . .

# Installer les dépendances PHP
RUN composer install --optimize-autoloader

# Exposer le port 9000 pour PHP-FPM
EXPOSE 9000

# Commande par défaut
CMD ["php-fpm"]
