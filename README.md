# Tents

Sistema de reserva de carpas en balnearios. Proyecto final de Programación en Ambiente Web, de la Universidad Nacional de Luján.

## Ejecución local

1. Clonar el proyecto:
```bash
git clone https://github.com/FranZoia6/Tents.git
```

2. Configurar las variables de entorno:
```bash
cd Tents/
cp .env.example .env
# Editar el fichero .env con los valores deseados.
```

3. Iniciar el entorno de desarrollo:
```bash
composer dev
```

4. Si es la primera vez que se inicia el entorno, se debe ingresar al Adminer para crear la base de datos.

5. Si es la primera vez que se inicia el entorno, se deben aplicar las migraciones de la base de datos según se especifica en la sección "Migraciones".

6. Al finalizar se puede limpiar el entorno local con el siguiente comando (no elimina el volumen de la base de datos):
```bash
composer dev-clear
```

## Migraciones

Las migraciones se deben crear y aplicar desde el contenedor del servidor web. Por ejemplo, para aplicar las migraciones en el entorno de desarrollo se debe ejecutar:
```bash
docker exec -t tents-webserver-1 vendor/bin/phinx migrate
```

## Integrantes

* Franco Zoia.
* Franco Parzanese.
* Leonardo Duville.
* Enzo Bianchi.
