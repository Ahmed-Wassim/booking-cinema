@extends('landlord.layouts.app')

@section('title', 'Payments')

@section('page-header')
@endsection

@section('page-title', 'Payments')
@section('page-subtitle', 'Monitor all system transactions')

@section('content')

    <div class="card shadow-sm border-0">
        <div class="card-body">

            {{-- Table --}}
            <div class="table-responsive">
                <table class="table align-middle table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Tenant</th>
                            <th>Plan</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Reference</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                            <tr>
                                <td>{{ $payment->created_at->format('M d, Y H:i') }}</td>
                                <td>
                                    <span class="fw-semibold">{{ $payment->tenant->id ?? 'Pending Tenant' }}</span>
                                    <br>
                                    <small class="text-muted">{{ $payment->tenant?->domains->first()->domain ?? $payment->tenant_id }}</small>
                                </td>
                                <td>{{ $payment->plan->name ?? 'N/A' }}</td>
                                <td>
                                    <span class="fw-bold">{{ $payment->currency }} {{ number_format($payment->amount, 2) }}</span>
                                </td>
                                <td>
                                    @if($payment->status === \App\Domain\Landlord\Enums\PaymentStatusEnum::PAID)
                                        <span class="badge bg-success">Paid</span>
                                    @elseif($payment->status === \App\Domain\Landlord\Enums\PaymentStatusEnum::PENDING)
                                        <span class="badge bg-warning">Pending</span>
                                    @else
                                        <span class="badge bg-danger">{{ ucfirst($payment->status->value) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">{{ $payment->transaction_ref ?? $payment->payment_token }}</small>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('landlord.payments.show', $payment->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> Details
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    No payments found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $payments->links() }}
            </div>

        </div>
    </div>

@endsection
