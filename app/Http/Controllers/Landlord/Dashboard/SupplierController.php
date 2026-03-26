<?php

namespace App\Http\Controllers\Landlord\Dashboard;

use App\Domain\Landlord\Dashboard\Web\Supplier\Repositories\Interfaces\ISupplierRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class SupplierController extends Controller
{
    public function __construct(
        protected ISupplierRepository $supplierRepository
    ) {
    }

    /**
     * List all suppliers with their settings (api_key masked).
     */
    public function index(): JsonResponse
    {
        $suppliers = $this->supplierRepository->listAllWithRelations(
            relations: ['setting'],
            orderBy: 'id',
            orderType: 'ASC'
        );

        $data = $suppliers->map(function ($supplier) {
            $item = [
                'id'        => $supplier->id,
                'key'       => $supplier->key,
                'name'      => $supplier->name,
                'type'      => $supplier->type,
                'status'    => $supplier->status,
                'created_at' => $supplier->created_at?->toIso8601String(),
            ];
            if ($supplier->relationLoaded('setting') && $supplier->setting) {
                $item['setting'] = [
                    'key'      => $supplier->setting->key,
                    'type'     => $supplier->setting->type,
                    'settings' => $this->maskSensitiveSettings($supplier->setting->settings ?? []),
                ];
            } else {
                $item['setting'] = null;
            }
            return $item;
        });

        return response()->json(['data' => $data]);
    }

    private function maskSensitiveSettings(array $settings): array
    {
        $masked = $settings;
        if (isset($masked['api_key']) && is_string($masked['api_key'])) {
            $len = strlen($masked['api_key']);
            $masked['api_key'] = $len > 4 ? '***' . substr($masked['api_key'], -4) : '***';
        }
        if (isset($masked['password']) && is_string($masked['password'])) {
            $masked['password'] = '***';
        }
        return $masked;
    }
}
