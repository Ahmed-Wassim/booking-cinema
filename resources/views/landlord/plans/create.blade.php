@extends('landlord.layouts.app')

@section('title', 'Create Plan')

@section('page-title', 'Create Plan')
@section('page-subtitle', 'Add new subscription plan to the system')

@section('content')

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <form action="{{ route('landlord.plans.store') }}" method="POST">
                @csrf

                @include('landlord.plans.partials.form')

                <div class="mt-4 border-top pt-3">
                    <button class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i> Save Plan
                    </button>
                    <a href="{{ route('landlord.plans.index') }}" class="btn btn-light">Cancel</a>
                </div>
            </form>

        </div>
    </div>

@endsection