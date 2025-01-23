# Utiliser une image PHP officielle avec Apache
FROM php:8.2-apache

# Ajouter le script pour installer les extensions PHP
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

# Donne les droits d'exécution au script
RUN chmod +x /usr/local/bin/install-php-extensions

# Installer les extensions PHP nécessaires
RUN install-php-extensions \
intl \
gd \
opcache \
pdo \
pdo_mysql \
zip \
mongodb

# Activer le mod_rewrite pour Apache (indispensable pour Symfony)
RUN a2enmod rewrite

# Configurer Apache pour pointer vers /public
RUN sed -i 's|/var/www/html|/var/www/html/public|' /etc/apache2/sites-available/000-default.conf

# Définir le dossier de travail dans le conteneur
WORKDIR /var/www/html

# Copier les fichiers du projet Symfony dans le conteneur
COPY . .


# Configurer les permissions pour Symfony
RUN chown -R www-data:www-data /var/www/html/var /var/www/html/public


