<?php

namespace Database\Seeders;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PrepareWorkflowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dumpPath = storage_path("app/dump/default_db.sql");

        $command = "mysqldump --user=".config('database.connections.mysql.username')." --password=".config('database.connections.mysql.password')." --host=".config('database.connections.mysql.host')." default_db > {$dumpPath}";

        exec($command, $output, $exitCode);

        if ($exitCode === 0) {
            $this->command->info("Database dump for default_db created successfully.");
        } else {
            $this->command->error("Failed to create database dump for default_db.");
        }
    }
}
