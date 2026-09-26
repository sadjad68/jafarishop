<?php

use App\Modules\User\Entities\Role;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $newPermission = 'admin.order.bijak';
        $fullOrderPermissions = [
            'admin.order.index',
            'admin.order.factor',
            'admin.order.detail',
            'admin.order.order-return',
            'admin.order.return',
            'admin.order.change-shipping-status',
            'admin.order.delete',
        ];

        Role::query()->each(function (Role $role) use ($newPermission, $fullOrderPermissions) {
            $permissions = unserialize($role->permission);
            if (!is_array($permissions) || in_array($newPermission, $permissions, true)) {
                return;
            }

            $hasFullOrderAccess = count(array_intersect($fullOrderPermissions, $permissions)) === count($fullOrderPermissions);
            if (!$hasFullOrderAccess && (int) $role->id !== 1) {
                return;
            }

            $permissions[] = $newPermission;
            $role->update([
                'permission' => serialize($permissions),
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $newPermission = 'admin.order.bijak';

        Role::query()->each(function (Role $role) use ($newPermission) {
            $permissions = unserialize($role->permission);
            if (!is_array($permissions)) {
                return;
            }

            $permissions = array_values(array_filter($permissions, function ($permission) use ($newPermission) {
                return $permission !== $newPermission;
            }));

            $role->update([
                'permission' => serialize($permissions),
            ]);
        });
    }
};
