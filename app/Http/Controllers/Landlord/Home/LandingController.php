<?php

namespace App\Http\Controllers\Landlord\Home;

use App\Domain\Landlord\Services\Interfaces\IPlanService;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class LandingController extends Controller
{
    public function __construct(
        protected IPlanService $planService
    ) {}

    /**
     * Display the landing page.
     */
    public function index(): View
    {
        $plans = $this->planService->listAllPlans();

        return view('home.landing', compact('plans'));
    }
}
