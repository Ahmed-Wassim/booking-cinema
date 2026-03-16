<?php

namespace App\Console\Commands;

use App\Domain\Landlord\Repositories\Interfaces\ISupplierRepository;
use App\Domain\Landlord\Services\Interfaces\IMovieSyncService;
use App\Models\Genre;
use App\Models\Movie;
use App\Models\Supplier;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TestMovieSyncCommand extends Command
{
    protected $signature = 'moviesync:test {--genres-only : Only sync genres}';

    protected $description = 'Test movie sync implementation (TMDB supplier)';

    public function handle(IMovieSyncService $syncService, ISupplierRepository $supplierRepo): int
    {
        $supplier = $supplierRepo->findByKey('tmdb');
        if (!$supplier instanceof Supplier) {
            $this->error('TMDB supplier not found. Run: php artisan db:seed --class=SupplierSeeder');
            return self::FAILURE;
        }

        $supplier->load('setting');
        if (!$supplier->setting || !($supplier->setting->settings['api_key'] ?? null)) {
            $this->warn('TMDB_API_KEY not set in .env - sync will exit early. Set it to test full sync.');
        }

        $start = microtime(true);

        if ($this->option('genres-only')) {
            $this->info('Syncing genres only...');
            $syncService->syncGenres($supplier);
            $duration = microtime(true) - $start;
            $this->info('Genres count: ' . Genre::count());
            $this->info(sprintf('Sync completed in %.2f seconds.', $duration));

Log::channel('time')->info('Movie sync (genres-only) completed.', [
                'supplier' => $supplier->key,
                'duration_seconds' => $duration,
            ]);

            return self::SUCCESS;
        }

        $this->info('Running full sync (genres + movies). This may take a minute...');
        $syncService->sync($supplier);
        $duration = microtime(true) - $start;

        $this->info('Genres: ' . Genre::count() . ', Movies: ' . Movie::count());
        $this->info(sprintf('Sync completed in %.2f seconds.', $duration));

        Log::channel('time')->info('Movie sync (full) completed.', [
            'supplier' => $supplier->key,
            'duration_seconds' => $duration,
        ]);

        return self::SUCCESS;
    }
}
