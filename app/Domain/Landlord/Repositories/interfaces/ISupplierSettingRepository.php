<?php

namespace App\Domain\Landlord\Repositories\Interfaces;

use App\Models\SupplierSetting;

interface ISupplierSettingRepository
{
    public function getBySupplierId(int $supplierId): ?SupplierSetting;
}
