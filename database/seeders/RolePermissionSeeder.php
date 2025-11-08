<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // App-level roles
        $roles = ['superAdmin', 'admin', 'user'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        // Global permissions
        $permissions = ['manage_users', 'manage_business', 'view_reports'];
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Assign permissions to roles
        $superAdminRole = Role::where('name', 'superAdmin')->first();
        $allPermissions = Permission::all();
        $superAdminRole->syncPermissions($allPermissions);

        $adminRole = Role::where('name', 'admin')->first();
        $adminPermissions = $allPermissions->whereIn('name', ['manage_business', 'view_reports']);
        $adminRole->syncPermissions($adminPermissions);

        $userRole = Role::where('name', 'user')->first();
        $userPermissions = $allPermissions->where('name', 'view_reports');
        $userRole->syncPermissions($userPermissions);
    }
}
