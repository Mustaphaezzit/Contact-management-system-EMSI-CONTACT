FROM php:8.2-apache

# Installer PDO MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Activer mod_rewrite seulement
RUN a2enmod rewrite

# Copier tous les fichiers du projet dans Apache
COPY . /var/www/html/

# Permissions
RUN chown -R www-data:www-data /var/www/html

# Exposer le port par défaut d'Apache
EXPOSE 80

# Lancer Apache en foreground (obligatoire pour Docker)
CMD ["apache2-foreground"]
