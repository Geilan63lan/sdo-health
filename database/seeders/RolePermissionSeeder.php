<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions
        $permissions = [
            'view_admin_panel',
            // Manage permissions (full CRUD)
            'manage_schools',
            'manage_students',
            'manage_health_records',
            'manage_vaccinations',
            'manage_health_programs',
            // View permissions (view only, no edit/add/delete)
            'view_schools',
            'view_students',
            'view_health_records',
            'view_vaccinations',
            'view_absences',
            'view_health_programs',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles
        $sdoAdminRole = Role::firstOrCreate(['name' => 'sdo_admin']);
        $healthCoordinatorRole = Role::firstOrCreate(['name' => 'health_coordinator']);
        $principalRole = Role::firstOrCreate(['name' => 'principal']);

        // Assign permissions to roles
        $sdoAdminRole->givePermissionTo($permissions); // SDO Admin has all permissions

        $healthCoordinatorRole->givePermissionTo([
            'view_admin_panel',
            'manage_students',
            'manage_health_records',
            'manage_vaccinations',
            'manage_health_programs',
            'view_absences',
        ]);

        $principalRole->givePermissionTo([
            'view_admin_panel',
            'view_schools',
            'view_students',
            'view_health_records',
            'view_vaccinations',
            'view_absences',
            'view_health_programs',
        ]);

        // Assign roles to existing users based on their current role column
        User::where('role', 'sdo_admin')->get()->each(function ($user) use ($sdoAdminRole) {
            $user->assignRole($sdoAdminRole);
        });

        User::where('role', 'health_coordinator')->get()->each(function ($user) use ($healthCoordinatorRole) {
            $user->assignRole($healthCoordinatorRole);
        });

        User::where('role', 'principal')->get()->each(function ($user) use ($principalRole) {
            $user->assignRole($principalRole);
        });
    }
}
