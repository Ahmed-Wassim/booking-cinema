@extends('landlord.layouts.app')

@section('title', __('landlord.Users'))

@section('page-header')
@endsection

@section('page-title', __('landlord.Users'))
@section('page-subtitle', __('landlord.Manage system users'))

@section('page-actions')
    @can('create', \App\Models\User::class)
    <a href="{{ route('landlord.users.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> {{ __('landlord.Add User') }}
    </a>
    @endcan
@endsection

@section('content')

    <div class="card shadow-sm border-0">
        <div class="card-body">

            {{-- Search --}}
            <form method="GET" action="{{ route('landlord.users.index') }}" class="row mb-3">
                <div class="col-md-4">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                        placeholder="{{ __('landlord.Search users...') }}">
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
                            <th>{{ __('landlord.Name') }}</th>
                            <th>{{ __('landlord.Email') }}</th>
                            <th>{{ __('landlord.Created') }}</th>
                            <th class="text-end">{{ __('landlord.Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->created_at->format('Y-m-d') }}</td>
                                <td class="text-end">

                                    @can('update', $user)
                                    <a href="{{ route('landlord.users.edit', $user->id) }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    @endcan

                                    @can('delete', $user)
                                    <form action="{{ route('landlord.users.destroy', $user->id) }}" method="POST"
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
                                    {{ __('landlord.No users found.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $users->links() }}
            </div>

        </div>
    </div>

@endsection