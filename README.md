Install
0) php81 composer.phar install --no-dev --optimize-autoloader
1) configure .env
2) php artisan storage:link
3) php artisan mini:prepareDefaultDB

For development

0) First, you need to deploy project. See 'Install'.
1) php artisan migrate
2) php artisan db:seed --class=DevSeeder
3) `npm run build` or `npm run dev`

Login user: admin 
password: adminadmin
