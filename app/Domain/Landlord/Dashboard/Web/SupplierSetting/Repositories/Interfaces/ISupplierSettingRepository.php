<?php

namespace App\Domain\Landlord\Dashboard\Web\SupplierSetting\Repositories\Interfaces;

use App\Models\SupplierSetting;

interface ISupplierSettingRepository
{
    public function getBySupplierId(int $supplierId): ?SupplierSetting;
}
