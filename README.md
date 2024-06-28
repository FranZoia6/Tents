# Tents

# Instalacion y Ejecucion (local)

git clone https://github.com/FranZoia6/Tents.git
cd Tents
composer install cd .env.example .env # Editar el .env los valores deseados
Ejecutar migrations: phinx migrate -e development
Ejecutar php -S localhost:8888 -t public