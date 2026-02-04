# Usamos PHP 7.4 con Apache
FROM php:7.4-apache

# 1. Instalamos dependencias del sistema incluyendo libzip-dev
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    libsqlite3-dev

# 2. Instalamos las extensiones de PHP (AQUÍ AGREGAMOS 'zip')
RUN docker-php-ext-install pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd zip

# 3. Habilitamos el módulo de reescritura de Apache
RUN a2enmod rewrite

# 4. Instalamos Composer de forma global
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# 5. Establecemos el directorio de trabajo
WORKDIR /var/www/html

# 6. Copiamos el contenido del proyecto
COPY . /var/www/html

# 7. Solucionar el error de Git "dubious ownership" y ejecutar Composer
RUN git config --global --add safe.directory /var/www/html && \
    composer install --no-interaction --optimize-autoloader --no-dev

# 8. Ajustamos los permisos de las carpetas
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

# 9. Configuramos Apache para que apunte a la carpeta /public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

EXPOSE 80