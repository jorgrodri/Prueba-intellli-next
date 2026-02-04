# Usamos PHP 7.4 con Apache (versión ideal para Laravel 5.8)
FROM php:7.4-apache

# Instalamos dependencias del sistema y librerías para SQLite y Excel
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    libsqlite3-dev

# Instalamos las extensiones de PHP necesarias para la prueba
RUN docker-php-ext-install pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd

# Habilitamos el módulo de reescritura de Apache para las rutas de Laravel
RUN a2enmod rewrite

# Establecemos el directorio de trabajo
WORKDIR /var/www/html

# Copiamos el contenido del proyecto al contenedor
COPY . /var/www/html

# Instalamos Composer de forma global dentro del contenedor
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Ajustamos los permisos de las carpetas de almacenamiento (Vital para que no falle el deploy)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

# Configuramos Apache para que apunte a la carpeta /public de Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Exponemos el puerto 80
EXPOSE 80
