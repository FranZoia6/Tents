# Imagen base.
FROM php:8.3-apache

# Copia los ficheros del sistema a la imagen.
COPY . /var/www/html/

# Copia la configuración de PHP (de momento la default para desarrollo).
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

# Establece el directorio de trabajo de la imagen.
WORKDIR /var/www/html/

# Declara los puertos expuestos.
EXPOSE 80
