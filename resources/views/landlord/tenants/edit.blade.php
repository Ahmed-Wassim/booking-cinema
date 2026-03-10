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

        </div>
    </div>

@endsection