<?php

namespace App\Domain\Landlord\Services\Classes;

use App\Domain\Landlord\Repositories\Interfaces\IPlanRepository;
use App\Domain\Landlord\Services\Interfaces\IPlanService;
use App\Models\Plan;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PlanService implements IPlanService
{
    public function __construct(
        protected IPlanRepository $planRepository
    ) {
    }

    public function listAllPlans(): Collection|array
    {
        return $this->planRepository->listAllBy();
    }

    public function storePlan(array $data): Model
    {
        DB::beginTransaction();
        $plan = $this->planRepository->create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'billing_interval' => $data['billing_interval'],
        ]);

        if (isset($data['features']) && is_array($data['features'])) {
            foreach ($data['features'] as $featureData) {
                $plan->features()->create([
                    'feature_key' => $featureData['feature_key'],
                    'feature_value' => $featureData['feature_value'],
                ]);
            }
        }

        DB::commit();

        return $plan;
    }

    public function editPlan(string|int $id): Model
    {
        // We use the eloquent model to quickly load features
        return Plan::with('features')->findOrFail($id);
    }

    public function updatePlan(array $data, string|int $id): Model
    {
        return DB::transaction(function () use ($data, $id) {
            $plan = $this->planRepository->update([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'price' => $data['price'],
                'billing_interval' => $data['billing_interval'],
            ], ['id' => $id]);

            // Sync features
            $plan->features()->delete();
            if (isset($data['features']) && is_array($data['features'])) {
                foreach ($data['features'] as $featureData) {
                    $plan->features()->create([
                        'feature_key' => $featureData['feature_key'],
                        'feature_value' => $featureData['feature_value'],
                    ]);
                }
            }

            return $plan;
        });
    }

    public function deletePlan(string|int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $this->planRepository->delete(['id' => $id]);
            return true;
        });
    }
}
