<?php

use App\Modules\User\Entities\Role;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * @return array<int, string>
     */
    private function videoPermissions(): array
    {
        return [
            'admin.video.index',
            'admin.video.create',
            'admin.video.edit',
            'admin.video.delete',
        ];
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $videoPermissions = $this->videoPermissions();
        $blogPermissions = [
            'admin.blog.index',
            'admin.blog.create',
            'admin.blog.edit',
            'admin.blog.delete',
        ];

        Role::query()->each(function (Role $role) use ($videoPermissions, $blogPermissions) {
            $permissions = unserialize($role->permission);
            if (!is_array($permissions)) {
                return;
            }

            $hasFullBlogAccess = count(array_intersect($blogPermissions, $permissions)) === count($blogPermissions);
            if (!$hasFullBlogAccess && (int) $role->id !== 1) {
                return;
            }

            $merged = array_values(array_unique(array_merge($permissions, $videoPermissions)));
            if ($merged === array_values($permissions)) {
                return;
            }

            $role->update([
                'permission' => serialize($merged),
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
        $videoPermissions = $this->videoPermissions();

        Role::query()->each(function (Role $role) use ($videoPermissions) {
            $permissions = unserialize($role->permission);
            if (!is_array($permissions)) {
                return;
            }

            $permissions = array_values(array_filter($permissions, function ($permission) use ($videoPermissions) {
                return !in_array($permission, $videoPermissions, true);
            }));

            $role->update([
                'permission' => serialize($permissions),
            ]);
        });
    }
};
