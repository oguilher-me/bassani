<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'manage_roles', 'description' => 'Manage user roles']);
        Permission::create(['name' => 'manage_permissions', 'description' => 'Manage role permissions']);
        Permission::create(['name' => 'view_dashboard', 'description' => 'View the dashboard']);
    }
}
