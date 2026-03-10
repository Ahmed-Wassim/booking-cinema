@extends('landlord.layouts.app')

@section('title', 'Create Tenant')

@section('page-title', 'Create Tenant')
@section('page-subtitle', 'Add new tenant to the system')

@section('content')

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <form action="{{ route('landlord.tenants.store') }}" method="POST">
                @csrf

                @include('landlord.tenants.partials.form')

                <div class="mt-3">
                    <button class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i> Save
                    </button>
                    <a href="{{ route('landlord.tenants.index') }}" class="btn btn-light">Cancel</a>
                </div>
            </form>

        </div>
    </div>

@endsection