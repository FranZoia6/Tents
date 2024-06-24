# Imagen base.
FROM php:8.3-apache

# Copia los ficheros del sistema a la imagen.
COPY . /var/www/html/

# Establece el directorio de trabajo de la imagen.
WORKDIR /var/www/html/

# Ejecuta comandos en una sola capa de la imagen para lograr lo siguiente:
# - Copia la configuración de PHP (de momento la default para desarrollo).
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini" && \
# - Instala git (requerido por Composer).
    apt update && \
    apt install -y git && \
    rm -rf /var/lib/apt/lists/* && \
# - Instala la última versión de Composer.
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php && \
    php -r "unlink('composer-setup.php');" && \
    mv composer.phar /usr/local/bin/composer && \
# - Instala las dependencias del sistema.
    composer install

# Declara los puertos expuestos.
EXPOSE 80

# Establece el "entrypoint" de la imagen.
ENTRYPOINT ["php", "-S", "0.0.0.0:80", "-t", "public"]
