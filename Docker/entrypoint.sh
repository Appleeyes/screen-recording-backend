#!/bin/bash

if [! -f "vendor/autoload.php"]; then
composer install --no-progress --no-interaction
npm install
fi

php artisan migrate --seed
php artisan key:generate
php artisan cache:clear
php artisan config:clear

php artisan serve --port=$PORT --host=0.0.0.0 --env=.env

exec docker-php-entrypoint "$@"