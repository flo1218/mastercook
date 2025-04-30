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


# Installer Node.js et npm
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www/symrecipe

# Copier les fichiers de l'application
COPY . .

# Installer les dépendances PHP
RUN composer install --optimize-autoloader

# Installer les dépendances npm
RUN npm install --legacy-peer-deps --no-audit --no-fund

# Construire les assets (si applicable)
RUN npm run build

# Donner les permissions nécessaires
# RUN chown -R www-data:www-data /var/www/symrecipe

# Exposer le port 9000 pour PHP-FPM
EXPOSE 9000

# Commande par défaut
CMD ["php-fpm"]
