<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SetupRoles extends Command
{
    protected $signature = 'roles:setup';
    protected $description = 'Set up default roles and permissions';

    public function handle()
    {
        // Create roles
        $roles = [
            'admin' => [
                'is_default' => false,
                'permissions' => ['*'] // All permissions
            ],
            'trainer' => [
                'is_default' => false,
                'permissions' => [
                    'view_courses',
                    'create_courses',
                    'edit_courses',
                    'delete_courses',
                    'manage_students',
                    'view_reports'
                ]
            ],
            'student' => [
                'is_default' => true, // Default role for new users
                'permissions' => [
                    'view_courses',
                    'enroll_courses',
                    'view_profile',
                    'edit_profile'
                ]
            ]
        ];

        foreach ($roles as $roleName => $config) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $role->update(['is_default' => $config['is_default']]);

            // Create and assign permissions
            if ($config['permissions'] !== ['*']) {
                foreach ($config['permissions'] as $permName) {
                    $permission = Permission::firstOrCreate(['name' => $permName, 'guard_name' => 'web']);
                    $role->givePermissionTo($permission);
                }
            }
        }

        $this->info('Roles and permissions set up successfully!');
    }
}
