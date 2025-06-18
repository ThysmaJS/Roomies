FROM php:8.2-apache

# Installe les extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip git curl libonig-dev \
    && docker-php-ext-install pdo pdo_mysql

# Installe Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configure Apache pour pointer vers le dossier public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

# Applique la nouvelle racine Apache et active mod_rewrite
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf \
 && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf \
 && a2enmod rewrite

# Positionne le répertoire de travail
WORKDIR /var/www/html

# Copie les sources Symfony (code local dans le conteneur)
COPY . .

# Déclare le dossier Git comme sûr + installe les dépendances PHP
RUN git config --global --add safe.directory /var/www/html \
 && composer install --no-interaction --prefer-dist

# Droits d'accès pour Apache
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
