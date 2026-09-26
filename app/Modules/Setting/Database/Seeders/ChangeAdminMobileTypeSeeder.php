<?php

namespace App\Modules\Setting\Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Setting\Entities\Setting;

class ChangeAdminMobileTypeSeeder extends Seeder
{
    /**
     * Change admin_mobile setting type from text to array.
     */
    public function run(): void
    {
        $setting = Setting::where('key', 'admin_mobile')->first();
        if (!$setting) {
            return;
        }

        if ($setting->type === 'array') {
            return;
        }

        $setting->update([
            'type' => 'array',
        ]);
    }
}
