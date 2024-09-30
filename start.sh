#!/bin/sh

# Set Render's expected port for Nginx
PORT=${PORT:-10000} # Utilise 10000 si Render le demande, sinon le port par défaut

# Remplace la ligne du port dans la config Nginx avec la variable d'environnement
sed -i "s/listen 80;/listen ${PORT};/" /etc/nginx/sites-available/default

# Démarre Nginx
service nginx start

# Démarre PHP-FPM
php-fpm
