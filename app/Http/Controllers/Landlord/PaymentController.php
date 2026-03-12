<?php

namespace App\Http\Controllers\Landlord;

use App\Domain\Landlord\DTO\PaymentDTO;
use App\Domain\Landlord\Services\Interfaces\IPaymentService;
use App\Domain\Landlord\Services\Interfaces\IPlanService;
use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(
        protected IPlanService $planService,
        protected IPaymentService $paymentService,
    ) {}

    /**
     * Show the plan selection page.
     * The tenant_id is stored in session by the registration flow.
     */
    public function plans(Request $request)
    {
        $plans = $this->planService->listAllPlans();
        $tenantId = $request->session()->get('pending_tenant_id');

        // If no pending tenant in session, redirect to registration
        if (! $tenantId) {
            return redirect()->route('landlord.register')
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
        $tenantId = $request->session()->get('pending_tenant_id');
        $tenantName = $request->session()->get('pending_tenant_name', 'Cinema Owner');
        $tenantEmail = $request->session()->get('pending_tenant_email', 'user@example.com');

        if (! $tenantId) {
            return redirect()->route('landlord.register')
                ->with('error', 'Please complete your registration first.');
        }

        try {
            $result = $this->paymentService->initiatePayment(
                (array) PaymentDTO::fromRequest([
                    'registration_id' => $request->session()->get('pending_registration_id'),
                    'tenant_id' => $tenantId,
                    'tenant_name' => $tenantName,
                    'tenant_email' => $tenantEmail,
                    'plan_id' => $plan->id,
                    'amount' => $plan->price,
                    'currency' => config('paytabs.currency', 'EGP'),
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
