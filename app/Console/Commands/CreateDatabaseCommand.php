<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use App\Database\Seeders\DatabaseSeeder;

class CreateDatabaseCommand extends Command
{
    protected $signature = 'database:create {name}';
    protected $description = 'Create a new database, run migrations and seed it';

    public function handle()
    {
        $dbName = "cms_".$this->argument('name');

        $this->createDatabase($dbName);

        Config::set('database.connections.dynamic', array_merge(
            config('database.connections.mysql'),
            [
                "username" => 'root',
                'database' => $dbName
            ]
        ));

        Artisan::call('migrate', [
            '--database' => 'dynamic',
            '--force' => true,
        ]);

        Artisan::call('db:seed', [
            '--database' => 'dynamic',
            '--force' => true,
            '--class' => DatabaseSeeder::class,
        ]);

        $this->info("Database `{$dbName}` created and migrations and seeders run successfully.");
    }

    protected function createDatabase($dbName)
    {
        $charset = config('database.connections.mysql.charset', 'utf8mb4');
        $collation = config('database.connections.mysql.collation', 'utf8mb4_unicode_ci');
        Config::set('database.connections.mysql.username', 'root');
        Config::set('database.connections.mysql.database', 'cms_localhost');
        DB::purge('mysql');

        $query = "CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET $charset COLLATE $collation;";

        DB::statement($query);
        $this->info("Database `{$dbName}` created successfully.");
    }
}
