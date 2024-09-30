# Utilise l'image officielle PHP 8.3 avec FPM
FROM php:8.3-fpm

# Installations de dépendances système
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libpq-dev \
    libzip-dev \
    zip \
    postgresql-client \   
    && docker-php-ext-install pdo pdo_pgsql zip

# Installe Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Crée un répertoire pour l'application
WORKDIR /var/www/html

# Copie les fichiers dans le conteneur
COPY . .

# Installe les dépendances PHP
RUN composer install --ignore-platform-req=ext-mongodb --ignore-platform-req=ext-gd --no-dev --optimize-autoloader 

# Permissions pour le stockage et le cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Copie le script de démarrage
COPY start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

# Expose le port 9000 pour PHP-FPM
EXPOSE 9000

# Lancer le script de démarrage quand le conteneur démarre
CMD ["sh", "/usr/local/bin/start.sh"]
