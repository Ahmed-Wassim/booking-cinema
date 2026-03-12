<?php

namespace App\Http\Controllers\Landlord;

use App\Domain\Landlord\Repositories\Interfaces\IPaymentRepository;
use App\Http\Controllers\Controller;
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
        // For now, let's just get all payments ordered by latest.
        // We'll use the model directly or add a listAll method to the repository if needed.
        // The repository already has a protected $model, but let's see if we should add a method to IPaymentRepository.
        
        // Let's assume we want to use the repository. I'll add a 'listAll' if it's not there.
        // Actually, many repositories here seem to have specific methods.
        
        $payments = \App\Models\Payment::with(['tenant', 'plan'])->latest()->paginate(20);

        return view('landlord.payments.index', compact('payments'));
    }

    /**
     * Display the specified payment.
     */
    public function show($id)
    {
        $payment = \App\Models\Payment::with(['tenant', 'plan'])->findOrFail($id);

        return view('landlord.payments.show', compact('payment'));
    }
}
