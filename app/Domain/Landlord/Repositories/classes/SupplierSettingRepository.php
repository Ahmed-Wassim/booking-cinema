<?php

namespace App\Domain\Landlord\Repositories\Classes;

use App\Domain\Landlord\Repositories\Interfaces\ISupplierSettingRepository;
use App\Models\SupplierSetting;

class SupplierSettingRepository implements ISupplierSettingRepository
{
    public function getBySupplierId(int $supplierId): ?SupplierSetting
    {
        $found = SupplierSetting::where('supplier_id', $supplierId)->first();
        return $found instanceof SupplierSetting ? $found : null;
    }
}
