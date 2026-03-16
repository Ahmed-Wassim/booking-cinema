<?php

namespace App\Http\Controllers\Landlord\Dashboard;

use App\Domain\Landlord\Repositories\Interfaces\IPaymentRepository;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class AdminPaymentController extends Controller
{
    public function __construct(
        protected IPaymentRepository $paymentRepository
    ) {
    }

    /**
     * Display a listing of all payments.
     */
    public function index()
    {
        $payments = Payment::with(['tenant', 'plan'])->latest()->paginate(20);

        return view('landlord.payments.index', compact('payments'));
    }

    /**
     * Display the specified payment.
     */
    public function show($id)
    {
        $payment = Payment::with(['tenant', 'plan'])->findOrFail($id);

        return view('landlord.payments.show', compact('payment'));
    }
}
