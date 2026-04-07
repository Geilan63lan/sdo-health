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
            'manage_schools',
            'manage_students',
            'manage_health_records',
            'manage_vaccinations',
            'manage_health_programs',
            'view_schools',
            'view_students',
            'manage_permissions',
            'view_health_records',
            'view_vaccinations',
            'view_absences',
            'view_health_programs',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission, 'guard_name' => 'web'],
                ['guard_name' => 'web']
            );
        }

        // Create roles
        $sdoAdminRole = Role::firstOrCreate(['name' => 'sdo_admin', 'guard_name' => 'web']);
        $healthCoordinatorRole = Role::firstOrCreate(['name' => 'health_coordinator', 'guard_name' => 'web']);
        $principalRole = Role::firstOrCreate(['name' => 'principal', 'guard_name' => 'web']);

        // Assign permissions to roles
        $sdoAdminRole->syncPermissions($permissions);

        $healthCoordinatorRole->syncPermissions([
            'view_admin_panel',
            'manage_students',
            'manage_health_records',
            'manage_vaccinations',
            'manage_health_programs',
            'view_absences',
        ]);

        $principalRole->syncPermissions([
            'view_admin_panel',
            'view_schools',
            'view_students',
            'view_health_records',
            'view_vaccinations',
            'view_absences',
            'view_health_programs',
        ]);

        // Assign roles to existing users based on their current role column
        User::all()->each(function (User $user) use ($sdoAdminRole, $healthCoordinatorRole, $principalRole) {
            if ($user->role === 'sdo_admin') {
                $user->assignRole($sdoAdminRole);
            } elseif ($user->role === 'health_coordinator') {
                $user->assignRole($healthCoordinatorRole);
            } elseif ($user->role === 'principal') {
                $user->assignRole($principalRole);
            }
        });
    }
}
