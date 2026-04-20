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
            // Admin permissions
            'view_admin_panel',
            'manage_schools',
            'manage_students',
            'manage_health_records',
            'manage_vaccinations',
            'manage_absences',
            'manage_health_programs',
            'manage_permissions',
            
            // View-only permissions
            'view_students',
            'view_schools',
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
        // SDO Admin has all permissions
        $sdoAdminRole->syncPermissions($permissions);

        // Health Coordinator - can manage health records, vaccinations, absences, and health programs
        $healthCoordinatorRole->syncPermissions([
            'view_admin_panel',
            'manage_students',
            'manage_health_records',
            'manage_vaccinations',
            'manage_absences',
            'manage_health_programs',
        ]);

        // Principal - can view students and health records
        $principalRole->syncPermissions([
            'view_admin_panel',
            'view_students',
            'view_health_records',
        ]);

        // Assign roles to existing users based on their current role column
        $sdo_admins = User::where('role', 'sdo_admin')->get();
        $sdo_admins->each(fn(User $user) => $user->assignRole($sdoAdminRole));

        $health_coordinators = User::where('role', 'health_coordinator')->get();
        $health_coordinators->each(fn(User $user) => $user->assignRole($healthCoordinatorRole));

        $principals = User::where('role', 'principal')->get();
        $principals->each(fn(User $user) => $user->assignRole($principalRole));
    }
}
