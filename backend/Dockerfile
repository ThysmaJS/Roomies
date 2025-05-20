FROM php:8.2-apache

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git unzip libicu-dev libpq-dev libzip-dev zip \
    && docker-php-ext-install intl pdo pdo_mysql zip

# Activer mod_rewrite d’Apache (important pour Symfony routing)
RUN a2enmod rewrite

# Modifier le DocumentRoot vers le dossier /public
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf

# Copier Composer depuis l'image officielle
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Créer le dossier de travail
WORKDIR /var/www/html

# Copier le VirtualHost custom pour que Symfony fonctionne bien
COPY apache/vhost.conf /etc/apache2/sites-available/000-default.conf

# Droits corrects pour Apache
RUN chown -R www-data:www-data /var/www/html

# Exposer le port Apache
EXPOSE 80
