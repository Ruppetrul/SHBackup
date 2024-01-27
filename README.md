Install
0) php81 composer.phar install --no-dev --optimize-autoloader
1) configure .env

For development

0) First, you need to deploy simply-shop and perform DevSeeder for create default_db

1) php artisan migrate
2) php artisan db:seed --class=DevSeeder
3) php artisan db:seed --class=PrepareWorkflowSeeder - Create actual db dump.

Login user: admin 
password: adminadmin
