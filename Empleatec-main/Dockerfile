# Usamos una imagen oficial de PHP con Apache
FROM php:8.1-apache

# Instalamos las extensiones necesarias para conectar con MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Copiamos todos los archivos de tu carpeta actual al servidor web de Docker
COPY . /var/www/html/

# Damos permisos para que las imágenes se puedan subir correctamente
RUN chown -R www-data:www-data /var/www/html