# Utilise une image PHP officielle avec Apache
FROM php:8.1-apache

# Installer les dépendances nécessaires pour Symfony
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev libzip-dev git unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip pdo pdo_mysql

# Activer mod_rewrite pour Symfony
RUN a2enmod rewrite

# Configurer le répertoire de travail
WORKDIR /var/www/html

# Copier le contenu du projet Symfony dans le conteneur
COPY . .

# Installer Composer (gestionnaire PHP)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Installer les dépendances du projet Symfony
RUN composer install --no-dev --optimize-autoloader

# Exposer le port 80 pour Apache
EXPOSE 80

# Démarrer Apache
CMD ["apache2-foreground"]
