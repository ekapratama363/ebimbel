<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $modules = config('permissions.modules', []);
        $actionLabels = config('permissions.actions', []);

        foreach ($modules as $module => $meta) {
            foreach ($meta['actions'] as $action) {
                $actionLabel = $actionLabels[$action] ?? ucfirst($action);
                Permission::query()->updateOrCreate(
                    ['module' => $module, 'action' => $action],
                    ['name' => $actionLabel.' '.$meta['label']],
                );
            }
        }

        $superAdmin = Role::query()->where('slug', 'super_admin')->first();
        if ($superAdmin) {
            $superAdmin->permissions()->sync(Permission::query()->pluck('id'));
        }
    }
}
