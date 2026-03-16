<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'dashboard.view',
            'users.view',
            'users.create',
            'users.update',
            'users.delete',
            'roles.view',
            'roles.create',
            'roles.update',
            'roles.delete',
            'notifications.view',
            'activity-logs.view',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        $admin = Role::findOrCreate('Admin', 'web');
        $manager = Role::findOrCreate('Manager', 'web');
        $member = Role::findOrCreate('Member', 'web');
        $readOnly = Role::findOrCreate('ReadOnly', 'web');

        $admin->syncPermissions($permissions);
        $manager->syncPermissions([
            'dashboard.view',
            'notifications.view',
        ]);
        $member->syncPermissions([
            'dashboard.view',
        ]);
        $readOnly->syncPermissions([
            'dashboard.view',
        ]);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
