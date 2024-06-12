#!/bin/bash

while ! nc -z mysql 3306; do
    echo "Waiting for MySQL to be available..."
    sleep 1
done

composer install

if [ ! -f .env ]; then
    cp .env.example .env
fi

php artisan key:generate

a2enmod rewrite

php artisan storage:link
php artisan migrate --force
php artisan db:seed --class=DevSeeder

exec apache2-foreground
