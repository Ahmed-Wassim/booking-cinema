@extends('landlord.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">Registration Requests</h1>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Pending and Processed Requests</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                        <thead class="table-light">
                            <tr>
                                <th>Cinema Name</th>
                                <th>Requested Subdomain</th>
                                <th>Contact Name</th>
                                <th>Email</th>
                                <th>Plan</th>
                                <th>Payment</th>
                                <th>Status</th>
                                <th>Submitted Date</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($requests as $request)
                                @php
                                    $payment = $request->getPaidPayment();
                                @endphp
                                <tr>
                                    <td>{{ $request->company_name }}</td>
                                    <td><code>{{ $request->domain }}</code></td>
                                    <td>{{ $request->name }}</td>
                                    <td><a href="mailto:{{ $request->email }}">{{ $request->email }}</a></td>
                                    <td>{{ $request->plan->name ?? 'N/A' }}</td>
                                    <td>
                                        @if($payment)
                                            @if($payment->status === \App\Domain\Landlord\Enums\PaymentStatusEnum::PAID)
                                                <span class="badge bg-success">Paid</span>
                                            @elseif($payment->status === \App\Domain\Landlord\Enums\PaymentStatusEnum::PENDING)
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @else
                                                <span class="badge bg-danger">{{ ucfirst($payment->status->value) }}</span>
                                            @endif
                                        @else
                                            <span class="text-muted small">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($request->status === \App\Domain\Landlord\Enums\RegistrationRequestStatusEnum::PENDING)
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @elseif($request->status === \App\Domain\Landlord\Enums\RegistrationRequestStatusEnum::APPROVED)
                                            <span class="badge bg-success">Approved</span>
                                        @else
                                            <span class="badge bg-danger">Rejected</span>
                                        @endif
                                    </td>
                                    <td>{{ $request->created_at->format('M d, Y H:i') }}</td>
                                    <td class="text-center">
                                        @if($request->status === \App\Domain\Landlord\Enums\RegistrationRequestStatusEnum::PENDING)
                                            <div class="d-flex justify-content-center gap-2">
                                                <form action="{{ route('landlord.registration-requests.approve', $request) }}"
                                                    method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success"
                                                        title="Approve & Create Tenant"
                                                        onclick="return confirm('Are you sure you want to approve this request? A new tenant and admin user will be created immediately.')">
                                                        <i class="bi bi-check-circle"></i> Approve
                                                    </button>
                                                </form>

                                                <form action="{{ route('landlord.registration-requests.reject', $request) }}"
                                                    method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                                        title="Reject Request"
                                                        onclick="return confirm('Are you sure you want to reject this request?')">
                                                        <i class="bi bi-x-circle"></i> Reject
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <span class="text-muted fst-italic">Processed</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">No registration requests found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    {{ $requests->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection