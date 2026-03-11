<?php

namespace App\Http\Controllers\Auth;

use App\Domain\Landlord\DTO\RegistrationRequestDTO;
use App\Domain\Landlord\Services\Interfaces\IPlanService;
use App\Domain\Landlord\Services\Interfaces\IRegistrationRequestService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\StoreRegistrationRequest;
use App\Models\Plan;
use Illuminate\Http\Request;

class LandingRegisterController extends Controller
{
    public function __construct(protected
        IRegistrationRequestService $registrationRequestService, protected
        IPlanService $planService
        )
    {
    }

    /**
     * Display the registration form.
     */
    public function create(Request $request)
    {
        $plans = $this->planService->listAllPlans();
        $selectedPlanId = $request->query('plan_id');

        return view('auth.register', compact('plans', 'selectedPlanId'));
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(StoreRegistrationRequest $request)
    {
        $this->registrationRequestService->storeRequest((array)RegistrationRequestDTO::fromRequest($request->validated()));

        return redirect()->route('landing')->with('success', 'Your registration request has been submitted. Our team will review it and get back to you shortly!');
    }
}
