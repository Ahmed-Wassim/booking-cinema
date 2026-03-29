<?php

namespace Database\Seeders\Tenant;

use App\Models\Tenant\User;
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
        $permissions = Permission::where('guard_name', 'tenant')->get();

        $tenantAdminRole = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'tenant',
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
