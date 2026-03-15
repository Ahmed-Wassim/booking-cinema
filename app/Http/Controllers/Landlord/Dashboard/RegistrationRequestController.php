<?php

namespace App\Http\Controllers\Landlord\Dashboard;

use App\Domain\Landlord\Enums\RegistrationRequestStatusEnum;
use App\Domain\Landlord\Services\Interfaces\ITenantService;
use App\Http\Controllers\Controller;
use App\Models\RegistrationRequest;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RegistrationRequestController extends Controller
{
    public function __construct(
        protected ITenantService $tenantService
    ) {}

    /**
     * Display a listing of the registration requests.
     */
    public function index()
    {
        $requests = RegistrationRequest::with('plan')->latest()->paginate(10);

        return view('landlord.registration_requests.index', compact('requests'));
    }

    /**
     * Approve the registration request.
     */
    public function approve(RegistrationRequest $registrationRequest)
    {
        if ($registrationRequest->status !== RegistrationRequestStatusEnum::PENDING) {
            return back()->with('error', 'Only pending requests can be approved.');
        }

        // 1. Create the Tenant via TenantService
        $tenantId = Str::slug($registrationRequest->company_name);

        // Ensure ID is unique
        if (Tenant::where('id', $tenantId)->exists()) {
            $tenantId = $tenantId.'-'.Str::random(4);
        }

        $latestPayment = $registrationRequest->getPaidPayment();

        $tenantData = [
            'id' => $tenantId,
            'domain' => $registrationRequest->domain,
            'plan_id' => $registrationRequest->plan_id,
            'payment_id' => $latestPayment ? $latestPayment->id : null,
        ];

        $tenant = $this->tenantService->storeTenant($tenantData);

        // 2. Run logic within the newly created Tenant context to create the Admin user
        $tenant->run(function () use ($registrationRequest) {
            User::create([
                'name' => $registrationRequest->name,
                'email' => $registrationRequest->email,
                'password' => $registrationRequest->password, // Already hashed from registration
            ]);
        });

        // 3. Mark request as approved
        $registrationRequest->update(['status' => RegistrationRequestStatusEnum::APPROVED]);

        return back()->with('success', 'Registration request approved. Tenant and Admin user created successfully.');
    }

    /**
     * Reject the registration request.
     */
    public function reject(RegistrationRequest $registrationRequest)
    {
        if ($registrationRequest->status !== RegistrationRequestStatusEnum::PENDING) {
            return back()->with('error', 'Only pending requests can be rejected.');
        }

        $registrationRequest->update(['status' => RegistrationRequestStatusEnum::REJECTED]);

        return back()->with('success', 'Registration request has been rejected.');
    }
}
