<?php

namespace App\Domain\Landlord\Services\Interfaces;


use App\Models\Supplier;

interface IMovieSyncService
{
    /** Sync genres and movies for the given supplier using its configured provider. */
    public function sync(Supplier $supplier): void;

    /** Sync by supplier key (e.g. "tmdb"). Loads supplier and setting, then syncs. */
    public function syncBySupplierKey(string $supplierKey): void;

    /** @deprecated Use syncBySupplierKey("tmdb") or sync($supplier) instead. */
    public function syncFromTmdb(): void;

    public function syncGenres(Supplier $supplier): void;

    public function syncMovies(Supplier $supplier): void;
}
