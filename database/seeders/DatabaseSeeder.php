<?php

namespace Database\Seeders;

use App\Models\Central\Tenant;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $tenant = Tenant::create([
            'id' => 'foo',
            'data' => [
                'name' => 'Acme Corporation',
                'email' => 'admin@acme.com',
            ],
        ]);

        $tenant->domains()->create([
            'domain' => 'foo.cinema.test',
        ]);
    }
}
