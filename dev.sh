#!/bin/bash

# Instala las dependencias del sistema.
composer install

# Inicia el servidor web.
php -S 0.0.0.0:80 -t public
