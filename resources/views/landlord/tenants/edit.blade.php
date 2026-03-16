@extends('landlord.layouts.app')

@section('title', 'Edit Tenant')

@section('page-title', 'Edit Tenant')
@section('page-subtitle', 'Update tenant information')

@section('content')

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <form action="{{ route('landlord.tenants.update', $tenant->id) }}" method="POST">
                @csrf
                @method('PUT')

                @include('landlord.tenants.partials.form')

                <div class="mt-3">
                    <button class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i> Update
                    </button>
                    <a href="{{ route('landlord.tenants.index') }}" class="btn btn-light">Cancel</a>
                </div>
            </form>

            <div class="mt-5 border-top pt-4">
                <h5 class="mb-3 text-white">Payment History</h5>
                @if($payments->isEmpty())
                    <div class="alert alert-dark text-muted border-0 bg-transparent px-0">No payments found for this tenant yet.</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-dark table-hover table-borderless align-middle">
                            <thead class="text-muted border-bottom border-secondary">
                                <tr>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Reference</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payments as $payment)
                                    <tr>
                                        <td>{{ $payment->created_at->format('M d, Y H:i') }}</td>
                                        <td class="fw-semibold text-white">{{ $payment->currency }} {{ number_format($payment->amount, 2) }}</td>
                                        <td>
                                            @if($payment->status === 'paid')
                                                <span class="badge bg-success bg-opacity-10 text-success px-2 py-1 rounded-pill">Paid</span>
                                            @elseif($payment->status === 'pending')
                                                <span class="badge bg-warning bg-opacity-10 text-warning px-2 py-1 rounded-pill">Pending</span>
                                            @else
                                                <span class="badge bg-danger bg-opacity-10 text-danger px-2 py-1 rounded-pill">{{ ucfirst($payment->status) }}</span>
                                            @endif
                                        </td>
                                        <td><small class="text-muted text-break">{{ $payment->transaction_ref ?? $payment->payment_token }}</small></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>
    </div>

@endsection