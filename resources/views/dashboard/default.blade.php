@extends('layouts.app')

@section('title', 'Dashboard - PWD System')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h1 class="h4 mb-0">Dashboard - Role Assignment Needed</h1>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="alert alert-warning">
                        <h2 class="h5">Welcome, {{ auth()->user()->name }}!</h2>
                        <p class="mb-1">
                            <strong>Current Role:</strong>
                            <span class="badge bg-secondary">{{ $role ?? 'Not assigned' }}</span>
                        </p>
                        <p class="mb-0 mt-2">
                            Your account needs proper role assignment to access the full system features.
                            Please contact the system administrator.
                        </p>
                    </div>

                    <!-- Debug Information -->
                    <div class="card mt-4">
                        <div class="card-header bg-info text-white">
                            <h3 class="h6 mb-0">Debug Information</h3>
                        </div>
                        <div class="card-body">
                            <p><strong>User ID:</strong> {{ $user->id }}</p>
                            <p><strong>Email:</strong> {{ $user->email }}</p>
                            <p><strong>Role in Database:</strong> {{ $user->role ?? 'NULL' }}</p>
                            <p><strong>Account Created:</strong> {{ $user->created_at->format('M j, Y g:i A') }}</p>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="row mt-4">
                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-user fa-2x text-primary mb-3"></i>
                                    <h3 class="h6">Profile</h3>
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('profile.show') }}" class="btn btn-outline-primary btn-sm">
                                            View Profile
                                        </a>
                                        <a href="{{ route('profile.edit') }}" class="btn btn-primary btn-sm">
                                            Edit Profile
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-universal-access fa-2x text-success mb-3"></i>
                                    <h3 class="h6">Accessibility</h3>
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('accessibility.settings') }}" class="btn btn-success btn-sm">
                                            Accessibility Settings
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-question-circle fa-2x text-info mb-3"></i>
                                    <h3 class="h6">Support</h3>
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('contact') }}" class="btn btn-info btn-sm">
                                            Contact Support
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
