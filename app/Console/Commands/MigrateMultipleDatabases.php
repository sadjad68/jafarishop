<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class MigrateMultipleDatabases extends Command
{
    protected $signature = 'migrate:multiple';
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

                // اجرای مایگریشن‌ها برای این دیتابیس
                Artisan::call('migrate', [
                    '--database' => 'dynamic',
                    '--force' => true,
                ]);

                $this->info("Migrated database: cms_{$row["site_name"]}");
            } catch (\Exception $e) {
                \Log::info($e->getMessage());
                $this->error("Database cms_{$row["site_name"]} does not exist or connection failed.");
            }
        }

        $this->info('All migrations completed!');
    }
}
