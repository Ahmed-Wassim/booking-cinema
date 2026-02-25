@extends('landlord.layouts.app')

@section('title', 'Dashboard')

@section('page-header')
@endsection

@section('page-title', 'Dashboard')
@section('page-subtitle', 'Welcome back, {{ auth()->user()->name ?? "User" }}! Here\'s what\'s happening.')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')

@endsection