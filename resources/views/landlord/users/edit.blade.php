@extends('landlord.layouts.app')

@section('title', 'Edit User')

@section('page-title', 'Edit User')
@section('page-subtitle', 'Update user information')

@section('content')

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                @include('users.partials.form')

                <div class="mt-3">
                    <button class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i> Update
                    </button>
                    <a href="{{ route('users.index') }}" class="btn btn-light">Cancel</a>
                </div>
            </form>

        </div>
    </div>

@endsection