#!/bin/bash

while ! nc -z mysql 3306; do
    echo "Waiting for MySQL to be available..."
    sleep 1
done

composer install

if [ ! -f .env ]; then
    cp .env.example .env
fi
chmod 777 ./storage/framework/sessions
chmod 777 ./storage/framework/views
chmod 777 ./storage/framework/cache/data

log_file="./storage/logs/laravel.log"
if [ -f "$log_file" ]; then
    chmod 777 "$log_file"
    echo "Permissions changed for $log_file"
fi

npm install vite
npm run build

php artisan key:generate

a2enmod rewrite

php artisan storage:link
php artisan migrate --force

if [ ! -d storage/app/dump ]; then mkdir -p storage/app/dump; fi
if [ -f /app/dump/default_db.sql ]; then rm /app/dump/default_db.sql; fi
php artisan mini:prepareDefaultDB
php artisan db:seed --class=DevSeeder

exec apache2-foreground
