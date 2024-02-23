<?php

namespace Modules\Mini\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class PrepareDefaultDB extends Command
{
    protected $signature = 'mini:prepareDefaultDB';
    protected $description = 'Prepares a database dump for further creation of shops.';

    public function handle()
    {
        $defaultDatabaseName = env('DB_DATABASE_DEFAULT');
        $this->checkAndDropIfExist($defaultDatabaseName);
        $this->createDatabase($defaultDatabaseName);
        $this->runMigrations();
        $this->info('Migrations are done.');
        $this->dump();
        $this->info('Dump file prepared.');
    }

    private function checkAndDropIfExist($databaseName)
    {
        $result = DB::select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?", [$databaseName]);

        if (count($result) > 0) {
            $this->dropDatabase($databaseName);
        }
    }

    private function dropDatabase($databaseName)
    {
        DB::purge();

        DB::disconnect($databaseName);

        DB::statement("DROP DATABASE IF EXISTS $databaseName");

        $this->info("Database '$databaseName' dropped.");
    }

    private function createDatabase($databaseName)
    {
        DB::purge();

        DB::disconnect();

        DB::statement("CREATE DATABASE IF NOT EXISTS $databaseName");

        $this->info("Database '$databaseName' created.");
    }

    private function runMigrations()
    {
        Artisan::call('migrate', [
            '--database' => 'default_sql',
            '--path' => 'Modules/Mini/Database/Migrations',
        ]);
    }

    private function dump()
    {
        $dumpPath = storage_path("app/dump/" . env('DB_DATABASE_DEFAULT') . ".sql");

        $command =
            "mysqldump --user=".config('database.connections.mysql.username')
            ." --password=".config('database.connections.mysql.password')
            ." --host=".config('database.connections.mysql.host')
            ." default_db > {$dumpPath}";

        exec($command, $output, $exitCode);

        if ($exitCode === 0) {
            $this->info("Database dump for default_db created successfully.");
        } else {
            $this->error("Failed to create database dump for default_db.");
        }
    }
}
