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
    public function __construct(protected IRegistrationRequestService $registrationRequestService, protected IPlanService $planService
    ) {}

    /**
     * Display the registration form.
     */
    public function create(Request $request)
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(StoreRegistrationRequest $request)
    {
        $data = $request->validated();
        $registrationRequest = $this->registrationRequestService->storeRequest((array) RegistrationRequestDTO::fromRequest($data));

        // Store info in session for payment flow
        $request->session()->put('pending_registration_id', $registrationRequest->id);
        $request->session()->put('pending_tenant_id', $data['domain']);
        $request->session()->put('pending_tenant_name', $data['name']);
        $request->session()->put('pending_tenant_email', $data['email']);

        // Instead of home, redirect to payment/plan selection
        return redirect()->route('landlord.payment.plans')->with('success', 'Registration submitted. Please select a plan and complete payment to activate your cinema!');
    }
}
