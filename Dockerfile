# Utiliser l'image officielle PHP 8.2 avec Apache
FROM php:8.2-apache

# Installer les extensions nécessaires
RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-configure zip \
    && docker-php-ext-install pdo pdo_sqlite zip

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www/webquickdetect

# Copier les fichiers de l'application dans le conteneur
COPY . .

# Installer les dépendances de Laravel
RUN composer install

# Donner les permissions appropriées aux dossiers de Laravel
RUN chown -R www-data:www-data /var/www/webquickdetect*
RUN chmod -R 775 /var/www/webquickdetect*
RUN chmod +x /var/www/webquickdetect/start.sh

# Exposer le port 80
EXPOSE 80
EXPOSE 8025

# Lancer le serveur Laravel
CMD ["./start.sh"]
