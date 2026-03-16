<?php

namespace Database\Seeders;

use App\Models\Supplier;
use App\Models\SupplierSetting;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $supplier = Supplier::firstOrCreate(
            ['key' => 'tmdb'],
            [
                'name' => 'TMDB',
                'type' => 'application/json',
                'status' => 'active',
            ]
        );

        SupplierSetting::updateOrCreate(
            ['supplier_id' => $supplier->id],
            [
                'key' => $supplier->key,
                'type' => 'application/json',
                'settings' => [
                    'api_key' => config('tmdb.key', env('TMDB_API_KEY', '')),
                    'api_url' => config('tmdb.base_url', env('TMDB_BASE_URL', 'https://api.themoviedb.org/3')),
                    'env_type' => config('app.env', 'production'),
                    'image_base_url' => config('tmdb.image_base_url', env('TMDB_IMAGE_BASE_URL', 'https://image.tmdb.org/t/p')),
                ],
            ]
        );
    }
}
