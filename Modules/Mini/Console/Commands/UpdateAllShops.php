<?php

namespace Modules\Mini\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class UpdateAllShops extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shops:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        echo 'Start shop updating';
        $shops = DB::table('shops')->get();

        foreach ($shops as $shop) {
            echo 'Update ' . $shop->db_name . PHP_EOL;
            if ($shop->db_name) {
                Config::set('database.connections.' . $shop->db_name, [
                    'driver' => 'mysql',
                    'host' => env('DB_HOST'),
                    'database' => $shop->db_name,
                    'username' => env('DB_USERNAME'),
                    'password' => env('DB_PASSWORD'),
                    'charset' => 'utf8mb4',
                    'collation' => 'utf8mb4_unicode_ci',
                    'prefix' => '',
                ]);

                DB::setDefaultConnection('shop');

                Artisan::call('migrate', [
                    '--database' => $shop->db_name,
                    '--path' => 'Modules/Mini/Database/Migrations',
                ]);
            }

            DB::reconnect('mysql');
        }
        echo 'Done !';
    }
}
