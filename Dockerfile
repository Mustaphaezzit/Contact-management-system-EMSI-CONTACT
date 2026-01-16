FROM php:8.2-apache

# Désactiver les MPM conflictuels et activer prefork
RUN a2dismod mpm_event mpm_worker || true
RUN a2enmod mpm_prefork

# Installer PDO MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Activer mod_rewrite
RUN a2enmod rewrite

# Copier le projet
COPY . /var/www/html/

# Permissions
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80

# Lancer Apache
CMD ["apache2-foreground"]
