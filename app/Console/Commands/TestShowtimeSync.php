<?php

namespace App\Console\Commands;

use App\Events\ShowtimeChanged;
use App\Models\Tenant;
use App\Models\Tenant\Branch;
use App\Models\Tenant\Movie;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TestShowtimeSync extends Command
{
    protected $signature = 'test:sync-showtime {tenant_id?}';
    protected $description = 'Test the Showtime Sync event pipeline to Go';

    public function handle()
    {
        $this->info("🔍 Starting E2E Sync Test...");

        // 1. Get a valid tenant
        $tenantId = $this->argument('tenant_id');
        $tenant = $tenantId ? Tenant::find($tenantId) : Tenant::first();

        if (!$tenant) {
            $this->error("❌ No tenants found in database.");
            return;
        }

        $this->info("✅ Found Tenant: {$tenant->id}");

        // 2. Fetch dependencies inside the tenant environment
        $branchId = null;
        $movieId = null;

        $tenant->run(function () use (&$branchId, &$movieId) {
            $branch = Branch::first();
            $movie = Movie::first();

            $branchId = $branch ? $branch->id : null;
            $movieId = $movie ? $movie->id : null;
        });

        if (!$branchId || !$movieId) {
            $this->error("❌ The selected tenant must have at least 1 Branch and 1 Movie populated to test the sync.");
            return;
        }

        $this->info("✅ Found Branch ID: {$branchId} & Movie ID: {$movieId}");

        // 3. Dispatch the Event (Simulating the controller behavior)
        $this->info("⏳ Dispatching ShowtimeChanged Event into Queue...");
        
        event(new ShowtimeChanged($tenant->id, $branchId, $movieId));

        $this->info("✅ Event Dispatched successfully! Make sure `php artisan queue:work` is running.");

        // 4. Test Go Search API directly to ensure we can hit it
        try {
            $goUrl = env('GO_SERVICE_URL', 'http://localhost:3003');
            $this->info("⏳ Making test call to Go search endpoint at {$goUrl}/search ...");
            
            $res = Http::timeout(3)->get("{$goUrl}/search?movie=a");
            
            if ($res->successful()) {
                $this->info("✅ Go endpoint reached successfully! JSON Response parsed.");
            } else {
                $this->warn("⚠️ Go endpoint reached but returned status: " . $res->status());
            }
        } catch (\Exception $e) {
            $this->warn("⚠️ Could not reach Go service directly via HTTP. Make sure it is running on port 3003.");
            $this->line("Error details: " . $e->getMessage());
        }

        $this->info("🚀 Test command finished. Proceed to MongoDB to verify your record was updated!");
    }
}
