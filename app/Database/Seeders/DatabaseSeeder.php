<?php

namespace App\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use App\Modules\General\Helper\ModuleUtils;
use App\Modules\Setting\Database\Seeders\ThemeSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //        $this->call(ThemeSeeder::class);
        $seeds_paths = [];
        foreach (config('modules.modules') as $row) {
            $path = ModuleUtils::app_module_path($row . "/Database/Seeders");
            foreach (File::allFiles($path) as $file) {
                $filename = pathinfo($file)['filename'];
                if ($filename === 'HighlightHomepageSeeder') {
                    continue;
                }
                $module_name = str_replace("/", "\\", $row);
                $seeds_paths[] = "App\Modules\\$module_name\Database\Seeders\\" . $filename;
            }
        }
//        $custom_seeder_order = [
//            'App\Modules\Setting\Database\Seeders\SettingsDatabaseSeeder',
//            'App\Modules\AnotherModule\Database\Seeders\SecondSeeder',
//        ];
//        $sorted_seeds_paths = array_merge($custom_seeder_order, array_diff($seeds_paths, $custom_seeder_order));
        foreach ($seeds_paths as $path) {
            $this->call($path);
        }
    }
}
