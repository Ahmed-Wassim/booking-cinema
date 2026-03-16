@extends('landlord.layouts.app')

@section('title', 'Edit Plan')

@section('page-title', 'Edit Plan')
@section('page-subtitle', 'Update an existing subscription plan')

@section('content')

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <form action="{{ route('landlord.plans.update', $plan->id) }}" method="POST">
                @csrf
                @method('PUT')

                @include('landlord.plans.partials.form')

                <div class="mt-4 border-top pt-3">
                    <button class="btn btn-warning text-dark">
                        <i class="bi bi-pencil-square me-1"></i> Update Plan
                    </button>
                    <a href="{{ route('landlord.plans.index') }}" class="btn btn-light">Cancel</a>
                </div>
            </form>

        </div>
    </div>

@endsection