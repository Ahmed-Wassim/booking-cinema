<?php

namespace App\Http\Controllers\Landlord;

use App\Domain\Landlord\Dashboard\Web\Payment\DTO\PaymentDTO;
use App\Domain\Landlord\Dashboard\Web\RegistrationRequest\Repositories\Interfaces\IRegistrationRequestRepository;
use App\Domain\Landlord\Dashboard\Web\Payment\Services\Interfaces\IPaymentService;
use App\Domain\Landlord\Dashboard\Web\Plan\Services\Interfaces\IPlanService;
use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(protected IPlanService $planService, protected IPaymentService $paymentService, protected IRegistrationRequestRepository $registrationRequestRepository,
    ) {}

    /**
     * Show the plan selection page.
     * The registration_id is stored in session by the registration flow.
     */
    public function plans(Request $request)
    {
        $plans = $this->planService->listAllPlans();
        $registrationId = $request->session()->get('pending_registration_id');

        // If no pending registration in session, redirect to registration
        if (! $registrationId) {
            return redirect()->route('register')
                ->with('error', 'Please complete your registration first.');
        }

        return view('payment.plans', compact('plans'));
    }

    /**
     * POST /pay/{plan}
     * Initiate the PayTabs payment session.
     */
    public function pay(Request $request, Plan $plan)
    {
        $registrationId = $request->session()->get('pending_registration_id');

        if (! $registrationId) {
            return redirect()->route('landlord.register')
                ->with('error', 'Please complete your registration first.');
        }

        $registrationRequest = $this->registrationRequestRepository->findOrFail($registrationId);

        try {
            $result = $this->paymentService->initiatePayment(
                (array) PaymentDTO::fromRequest([
                    'registration_id' => $registrationId,
                    'tenant_name' => $registrationRequest->name,
                    'tenant_email' => $registrationRequest->email,
                    'plan_id' => $plan->id,
                    'amount' => $plan->price,
                    'currency' => $plan->currency ?? config('paytabs.currency', 'EGP'),
                    'return_url' => route('landlord.payment.success'),
                    'callback_url' => route('landlord.payment.callback'),
                ])
            );

            // Store payment_id in session for tracking
            $request->session()->put('current_payment_id', $result['payment_id']);

            return redirect()->away($result['redirect_url']);
        } catch (\Exception $e) {
            return redirect()->route('landlord.payment.plans')
                ->with('error', 'Could not initiate payment. Please try again. '.$e->getMessage());
        }
    }

    /**
     * PayTabs server-to-server callback (no CSRF, no auth).
     */
    public function callback(Request $request)
    {
        $success = $this->paymentService->handleCallback($request->all());

        // PayTabs expects a 200 response
        return response()->json(['status' => $success ? 'ok' : 'failed'], 200);
    }

    /**
     * User is redirected here after a successful payment on PayTabs.
     */
    public function success(Request $request)
    {
        $request->session()->forget(['pending_tenant_id', 'pending_tenant_name', 'pending_tenant_email', 'current_payment_id']);

        return view('payment.success');
    }

    /**
     * User is redirected here after a failed / cancelled payment on PayTabs.
     */
    public function failure(Request $request)
    {
        $tenantId = $request->session()->get('pending_tenant_id');

        return view('payment.failure', compact('tenantId'));
    }
}
