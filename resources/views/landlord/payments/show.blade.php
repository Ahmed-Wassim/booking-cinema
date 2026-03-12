@extends('landlord.layouts.app')

@section('title', 'Payment Details')

@section('page-title', 'Payment Details')
@section('page-subtitle', 'Detailed transaction information')

@section('content')

    <div class="row">
        <div class="col-md-5">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Transaction Info</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">Status</span>
                            <span>
                                @if($payment->status === 'paid')
                                    <span class="badge bg-success">Paid</span>
                                @elseif($payment->status === 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @else
                                    <span class="badge bg-danger">{{ ucfirst($payment->status) }}</span>
                                @endif
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">Amount</span>
                            <span class="fw-bold text-primary">{{ $payment->currency }} {{ number_format($payment->amount, 2) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">Tenant</span>
                            <span>{{ $payment->tenant->id ?? $payment->tenant_id ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">Plan</span>
                            <span>{{ $payment->plan->name ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">Date</span>
                            <span>{{ $payment->created_at->format('M d, Y H:i:s') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">Cart ID / Token</span>
                            <span class="text-break ms-3">{{ $payment->payment_token }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0 border-0">
                            <span class="text-muted">Transaction Ref</span>
                            <span class="text-muted text-break ms-3">{{ $payment->transaction_ref ?? 'N/A' }}</span>
                        </li>
                    </ul>

                    <div class="mt-4">
                        <a href="{{ route('landlord.payments.index') }}" class="btn btn-light w-100">Back to List</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">PayTabs Response Data</h5>
                </div>
                <div class="card-body bg-light">
                    @if($payment->callback_data)
                        <pre class="mb-0" style="max-height: 500px; overflow-y: auto;"><code>{{ json_encode($payment->callback_data, JSON_PRETTY_PRINT) }}</code></pre>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-info-circle text-muted fs-1 d-block mb-3"></i>
                            <p class="text-muted mb-0">No callback data available for this transaction yet.</p>
                            <small class="text-muted">(This typically means the payment is still pending or the callback was not received)</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
