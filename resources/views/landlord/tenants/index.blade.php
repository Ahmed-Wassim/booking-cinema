@extends('landlord.layouts.app')

@section('title', 'Tenants')

@section('page-header')
@endsection

@section('page-title', 'Tenants')
@section('page-subtitle', 'Manage system tenants')

@section('page-actions')
    <a href="{{ route('landlord.tenants.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Add Tenant
    </a>
@endsection

@section('content')

    <div class="card shadow-sm border-0">
        <div class="card-body">

            {{-- Search --}}
            <form method="GET" action="{{ route('landlord.tenants.index') }}" class="row mb-3">
                <div class="col-md-4">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                        placeholder="Search tenants...">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-outline-primary w-100">Search</button>
                </div>
            </form>

            {{-- Table --}}
            <div class="table-responsive">
                <table class="table align-middle table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>ID (Subdomain prefix)</th>
                            <th>Domain</th>
                            <th>Created</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tenants as $tenant)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $tenant->id }}</td>
                                <td>{{ $tenant->domains->first()->domain ?? 'N/A' }}</td>
                                <td>{{ $tenant->created_at->format('Y-m-d') }}</td>
                                <td class="text-end">

                                    <a href="{{ route('landlord.tenants.edit', $tenant->id) }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <form action="{{ route('landlord.tenants.destroy', $tenant->id) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">
                                    No tenants found.
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