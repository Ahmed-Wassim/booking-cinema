<?php

declare(strict_types=1);

namespace App\Domain\Landlord\MovieSync\Interfaces;

use App\Domain\Shared\Suppliers\Contracts\MovieSupplier;
use App\Models\Supplier;

interface IGenreSyncService
{
    public function sync(Supplier $supplier, MovieSupplier $provider): void;
}
