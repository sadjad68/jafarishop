<?php

namespace App\Modules\Setting\Database\Seeders;

use Illuminate\Support\Facades\Config;
use App\Modules\Setting\Entities\Setting;
use Illuminate\Database\Seeder;

class SettingsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(DeleteFieldsSeeder::class);
        $partials = Config::get('setting_structure.setting_partials');
        foreach ($partials as $partial) {
            foreach ($partial['fields'] as $field) {
                $this->createSettingRecord($field['key'], $field);
            }
            foreach ($partial['partials'] as $sub_partial) {
                foreach ($sub_partial['fields'] as $field) {
                    $this->createSettingRecord($field['key'], $field);
                }
            }
        }
    }

    private function createSettingRecord($key, $data)
    {
        $record = Setting::where('key', $key)->first();
        if (!$record) {
            if ($data['type'] == "menu" || $data['type'] == "work_hours" || $data['type'] == "footer" || $data['type'] == "select") {
                $data['value'] = json_encode($data['value']);
            }
            $record = Setting::create($data);
        } else {
            $record->options = $data['options'];
        }

        $record->p_name = $data['p_name'];
        $record->theme_type = $data['theme_type'];
        $record->save();
    }
}

