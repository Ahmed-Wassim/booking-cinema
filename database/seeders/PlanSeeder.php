<?php

namespace Database\Seeders;

use App\Domain\Landlord\Enums\FeatureKeyEnum;
use App\Models\Plan;
use Illuminate\Database\Seeder;

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
                'features' => [
                    FeatureKeyEnum::MAX_CINEMAS->value => '1',
                    FeatureKeyEnum::MAX_HALLS->value => '3',
                    FeatureKeyEnum::MAX_BOOKINGS->value => '500',
                ],
            ],
            [
                'name' => 'Pro',
                'description' => 'For standard cinemas.',
                'price' => 150.00,
                'billing_interval' => 'monthly',
                'features' => [
                    FeatureKeyEnum::MAX_CINEMAS->value => '5',
                    FeatureKeyEnum::MAX_HALLS->value => '15',
                    FeatureKeyEnum::MAX_BOOKINGS->value => '5000',
                ],
            ],
            [
                'name' => 'Premium',
                'description' => 'For large cinema chains.',
                'price' => 300.00,
                'billing_interval' => 'monthly',
                'features' => [
                    FeatureKeyEnum::MAX_CINEMAS->value => '15',
                    FeatureKeyEnum::MAX_HALLS->value => 'unlimited',
                    FeatureKeyEnum::MAX_BOOKINGS->value => 'unlimited',
                ],
            ],
        ];

        foreach ($plans as $planData) {
            $features = $planData['features'];
            unset($planData['features']);

            $plan = Plan::updateOrCreate(['name' => $planData['name']], $planData);

            foreach ($features as $key => $value) {
                $plan->features()->updateOrCreate(
                ['feature_key' => $key],
                ['feature_value' => $value]
                );
            }
        }
    }
}
