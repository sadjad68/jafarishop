<?php

namespace App\Modules\User\Database\Seeders;

use App\Modules\User\Entities\Role;
use App\Modules\User\Entities\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class UserDatabaseUpdateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $full_access = ["admin.dashboard"];
        foreach (Config::get('site.permissions') as $key => $permission) {
            if (isset($permission['access'])) {
                foreach ($permission['access'] as $keyAccess => $access) {
                    $full_access[] = 'admin.' . $key . '.' . $keyAccess;
                }
            }
        }
        $role = Role::first();
        if ($role){
            $role->update([
                'permission' => serialize($full_access)
            ]);
        }

    }
}
