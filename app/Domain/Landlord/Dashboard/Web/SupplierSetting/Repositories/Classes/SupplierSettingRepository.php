<?php

namespace App\Domain\Landlord\Dashboard\Web\SupplierSetting\Repositories\Classes;

use App\Domain\Landlord\Dashboard\Web\SupplierSetting\Repositories\Interfaces\ISupplierSettingRepository;
use App\Models\SupplierSetting;

class SupplierSettingRepository implements ISupplierSettingRepository
{
    public function getBySupplierId(int $supplierId): ?SupplierSetting
    {
        $found = SupplierSetting::where('supplier_id', $supplierId)->first();
        return $found instanceof SupplierSetting ? $found : null;
    }
}
