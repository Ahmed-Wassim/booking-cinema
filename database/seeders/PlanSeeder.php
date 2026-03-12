<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Plan;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Basic',
                'description' => 'For small cinemas.',
                'price' => 50.00,
                'billing_interval' => 'monthly',
            ],
            [
                'name' => 'Pro',
                'description' => 'For standard cinemas.',
                'price' => 150.00,
                'billing_interval' => 'monthly',
            ],
            [
                'name' => 'Premium',
                'description' => 'For large cinema chains.',
                'price' => 300.00,
                'billing_interval' => 'monthly',
            ],
        ];

        foreach ($plans as $plan) {
            Plan::updateOrCreate(['name' => $plan['name']], $plan);
        }
    }
}
