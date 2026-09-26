<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class SeedMultipleDatabases extends Command
{
    protected $signature = 'db:seedmulti {class}';
    protected $description = 'Run migrations on multiple databases';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $jsonData = Storage::disk('local')->get('private/config-sites-data.json');
        $sites = json_decode($jsonData, true);

        foreach ($sites as $row) {
            $this->info("Checking database: cms_{$row["site_name"]}");

            // تنظیم کانکشن به صورت داینامیک
            Config::set('database.connections.dynamic', array_merge(
                config('database.connections.mysql'),
                [
                    "username" => 'root',
                    'database' => 'cms_'.$row["site_name"]
                ]
            ));

            DB::purge('dynamic');

            try {
                // چک کردن اتصال به دیتابیس
                DB::connection('dynamic')->getPdo();
                $this->info("Migrating database: cms_{$row["site_name"]}");

                $class = $this->argument('class');

                // اجرای سیدرها برای این دیتابیس
                Artisan::call('db:seed', [
                    '--database' => 'dynamic',
                    '--force' => true,
                    '--class' => $class,
                ]);

                $this->info("Seed database: cms_{$row["site_name"]}");
            } catch (\Exception $e) {
                $this->error("Database cms_{$row["site_name"]} does not exist or connection failed.");
            }
        }

        $this->info('All Seeds completed!');
    }
}
