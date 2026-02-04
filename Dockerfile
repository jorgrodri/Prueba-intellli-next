# Usamos PHP 7.4 con Apache
FROM php:7.4-apache

# 1. Instalamos dependencias del sistema
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    libsqlite3-dev

# 2. Instalamos las extensiones de PHP
RUN docker-php-ext-install pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd

# 3. Habilitamos el módulo de reescritura de Apache
RUN a2enmod rewrite

# 4. Instalamos Composer de forma global
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# 5. Establecemos el directorio de trabajo
WORKDIR /var/www/html

# 6. Copiamos el contenido del proyecto
COPY . /var/www/html

# --- ESTA ES LA PARTE QUE TE FALTA ---
# Ejecutamos la instalación de las dependencias
RUN composer install --no-interaction --optimize-autoloader --no-dev
# -------------------------------------

# 7. Ajustamos los permisos (después de instalar las librerías)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

# 8. Configuramos Apache para que apunte a la carpeta /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

EXPOSE 80