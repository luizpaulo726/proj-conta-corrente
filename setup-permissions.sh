#!/bin/bash

# Definindo as permissões do diretório storage
echo "Configuring permissions for storage and logs..."

# Configurar as permissões no diretório storage
sudo chown -R www-data:www-data /var/www/html/storage
sudo chmod -R 775 /var/www/html/storage

# Configurar as permissões no arquivo laravel.log
sudo chown www-data:www-data /var/www/html/storage/logs/laravel.log
sudo chmod 664 /var/www/html/storage/logs/laravel.log

echo "Permissions configured successfully!"
