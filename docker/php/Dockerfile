FROM php:8.2-apache

# Extensiones necesarias para MySQL y PDO
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Habilitar mod_rewrite para el router
RUN a2enmod rewrite

# Configurar document root
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

EXPOSE 80