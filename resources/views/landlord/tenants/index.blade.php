@extends('landlord.layouts.app')

@section('title', __('landlord.Tenants'))

@section('page-header')
@endsection

@section('page-title', __('landlord.Tenants'))
@section('page-subtitle', __('landlord.Manage system tenants'))

@section('page-actions')
    @can('create', \App\Models\Tenant::class)
    <a href="{{ route('landlord.tenants.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> {{ __('landlord.Add Tenant') }}
    </a>
    @endcan
@endsection

@section('content')

    <div class="card shadow-sm border-0">
        <div class="card-body">

            {{-- Search --}}
            <form method="GET" action="{{ route('landlord.tenants.index') }}" class="row mb-3">
                <div class="col-md-4">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                        placeholder="{{ __('landlord.Search tenants...') }}">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-outline-primary w-100">{{ __('landlord.Search') }}</button>
                </div>
            </form>

            {{-- Table --}}
            <div class="table-responsive">
                <table class="table align-middle table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>{{ __('landlord.Tenant ID') }}</th>
                            <th>{{ __('landlord.Domain') }}</th>
                            <th>{{ __('landlord.Subscription Plan') }}</th>
                            <th class="text-end">{{ __('landlord.Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tenants as $tenant)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><span class="fw-semibold">{{ $tenant->id }}</span></td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $tenant->domains->first()->domain ?? __('landlord.N/A') }}.{{ request()->getHost() }}
                                    </span>
                                </td>
                                <td>
                                    @if($activeSub = $tenant->subscriptions->firstWhere('status', 'active'))
                                        <span class="badge bg-primary">{{ $activeSub->plan->name ?? __('landlord.Unknown Plan') }}</span>
                                    @else
                                        <span class="badge bg-danger">{{ __('landlord.None') }}</span>
                                    @endif
                                </td>
                                <td class="text-end">

                                    @can('update', $tenant)
                                    <a href="{{ route('landlord.tenants.edit', $tenant->id) }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    @endcan

                                    @can('delete', $tenant)
                                    <form action="{{ route('landlord.tenants.destroy', $tenant->id) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('{{ __('landlord.Are you sure?') }}')">
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
                                    {{ __('landlord.No tenants found.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $tenants->links() }}
            </div>

        </div>
    </div>

@endsection