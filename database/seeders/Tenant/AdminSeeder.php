<?php

namespace Database\Seeders\Tenant;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = Permission::all();

        $tenantAdminRole = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $tenantAdminRole->syncPermissions($permissions);

        $adminUser = User::firstOrCreate(
            ['email' => 'tenant-admin@'.tenant('id').'.com'], // Unique email per tenant
            [
                'name' => 'Tenant Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $adminUser->assignRole($tenantAdminRole);
    }
}
