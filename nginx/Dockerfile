# Utiliser l'image officielle de Nginx
FROM nginx:alpine

# Copier le fichier de configuration personnalisé de Nginx
COPY ./nginx/default.conf /etc/nginx/conf.d/default.conf

# Exposer le port 9321 pour le serveur Nginx
EXPOSE 9321

# Commande de démarrage Nginx
CMD ["nginx", "-g", "daemon off;"]