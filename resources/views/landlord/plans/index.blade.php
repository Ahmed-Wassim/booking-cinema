@extends('landlord.layouts.app')

@section('title', 'Plans')

@section('page-title', 'Plans')
@section('page-subtitle', 'Manage subscription plans and tier limits')

@section('page-actions')
    @can('create', \App\Models\Plan::class)
    <a href="{{ route('landlord.plans.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Add Plan
    </a>
    @endcan
@endsection

@section('content')

    <div class="card shadow-sm border-0">
        <div class="card-body">

            {{-- Table --}}
            <div class="table-responsive mt-3">
                <table class="table align-middle table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Billing Interval</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($plans as $plan)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $plan->name }}</div>
                                <td>{{ $currentCurrency->getSymbol() }}{{ number_format($plan->price, 2) }}</td>
                                <td><span class="badge bg-secondary text-capitalize">{{ $plan->billing_interval }}</span></td>
                                <td class="text-end">
                                    @can('update', $plan)
                                    <a href="{{ route('landlord.plans.edit', $plan->id) }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    @endcan

                                    @can('delete', $plan)
                                    <form action="{{ route('landlord.plans.destroy', $plan->id) }}" method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Are you sure you want to delete this plan?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">
                                    No plans found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

@endsection