# Simply-shop
## Install
0) php81 composer.phar install --no-dev --optimize-autoloader
1) configure .env
   - db
   - SMTP
2) php artisan storage:link
3) configure DB_DATABASE_DEFAULT in ENV.
4) create storage/app/dump
5) php artisan mini:prepareDefaultDB
6) php artisan queue:work --daemon

## For development
0) First, you need to deploy project. See 'Install'.
1) php artisan migrate --path=/database/migrations
2) php artisan db:seed --class=DevSeeder
3) `npm run build` or `npm run dev`

## For migration
0) php artisan mini:prepareDefaultDB
1) php artisan shops:update

## Admin user
Login user: admin 
password: adminadmin
