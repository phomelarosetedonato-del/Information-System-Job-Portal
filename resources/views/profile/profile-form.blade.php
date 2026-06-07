@extends('layouts.app')

@section('title')
    {{ isset($mode) && $mode === 'edit' ? 'Edit Profile - PWD System' : 'Complete PWD Profile - PWD System' }}
@endsection

@section('content')
<div class="container-fluid px-0">
    <!-- Page Header -->
    <div class="dashboard-header bg-white border-bottom py-3 py-md-4">
        <div class="container">
            <div class="row align-items-center g-3">
                <div class="col-12 col-md-8">
                    <h1 class="h4 h3-md mb-2 text-dark">
                        <i class="fas {{ isset($mode) && $mode === 'edit' ? 'fa-user-edit' : 'fa-clipboard-check' }} me-2 text-primary"></i>
                        {{ isset($mode) && $mode === 'edit' ? 'Edit Profile' : 'Complete Your PWD Profile' }}
                    </h1>
                    <p class="mb-0 text-muted small">
                        {{ isset($mode) && $mode === 'edit' ? 'Update your personal information and PWD details' : 'Please provide your PWD information to access all features and opportunities.' }}
                    </p>
                </div>
                <div class="col-12 col-md-4 text-md-end">
                    <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary w-100 w-md-auto">
                        <i class="fas fa-arrow-left me-2"></i> Back to Profile
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Session Messages -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Profile Completion Progress -->
                @if($user->isPwd())
                @php
                    $completionPercentage = $user->getProfileCompletionPercentage();
                    $progressColor = $completionPercentage >= 80 ? 'success' : ($completionPercentage >= 50 ? 'warning' : 'danger');
                @endphp
                <div class="alert alert-{{ $progressColor }} alert-dismissible fade show mb-4" role="alert">
                    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center gap-3">
                        <div class="flex-grow-1">
                            <h6 class="alert-heading mb-2">
                                <i class="fas fa-chart-line me-2"></i>Profile Completion: {{ $completionPercentage }}%
                            </h6>
                            <div class="progress mb-2" style="height: 25px;">
                                <div class="progress-bar bg-{{ $progressColor }}" role="progressbar"
                                     style="width: {{ $completionPercentage }}%;"
                                     aria-valuenow="{{ $completionPercentage }}" aria-valuemin="0" aria-valuemax="100">
                                    <strong>{{ $completionPercentage }}%</strong>
                                </div>
                            </div>
                            <p class="mb-0 small">
                                @if($completionPercentage >= 80)
                                    <i class="fas fa-check-circle me-1"></i>Your profile is complete! You can apply for jobs.
                                @else
                                    <i class="fas fa-info-circle me-1"></i>Complete at least 80% to apply for jobs. Fill in more details below.
                                @endif
                            </p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <form method="POST" action="{{ route('profile.form.submit', ['mode' => $mode ?? 'complete']) }}" enctype="multipart/form-data" id="profileUpdateForm">
                    @csrf
                    @if(isset($mode) && $mode === 'edit')
                        @method('PUT')
                    @endif

                    <!-- Debug: Show form action and method -->
                    <div style="display: none;">
                        <input type="hidden" value="{{ route('profile.form.submit', ['mode' => $mode ?? 'complete']) }}" id="debugFormAction">
                        <input type="hidden" value="{{ isset($mode) && $mode === 'edit' ? 'PUT' : 'POST' }}" id="debugFormMethod">
                        <input type="hidden" value="{{ $mode ?? 'complete' }}" id="debugMode">
                    </div>

                    @include('profile.partials.profile-form-fields', ['user' => $user, 'pwdProfile' => $pwdProfile, 'disabilityTypes' => $disabilityTypes, 'skillOptions' => $skillOptions, 'qualificationOptions' => $qualificationOptions, 'accommodationOptions' => $accommodationOptions, 'workArrangementOptions' => $workArrangementOptions ?? null, 'assistiveDeviceOptions' => $assistiveDeviceOptions ?? null])

                    <!-- Form Actions -->
                    <div class="card shadow-sm border-0 sticky-bottom-mobile">
                        <div class="card-body p-3 p-md-4">
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-stretch align-items-md-center gap-2 gap-md-3">
                                <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary order-3 order-md-1">
                                    <i class="fas fa-arrow-left me-2"></i>
                                    <span class="d-none d-sm-inline">Back to Profile</span>
                                    <span class="d-inline d-sm-none">Back</span>
                                </a>
                                <div class="d-flex flex-column flex-sm-row gap-2 order-1 order-md-2">
                                    <button type="reset" class="btn btn-outline-danger">
                                        <i class="fas fa-undo me-2"></i>
                                        <span class="d-none d-sm-inline">Reset Changes</span>
                                        <span class="d-inline d-sm-none">Reset</span>
                                    </button>
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <i class="fas fa-save me-2"></i>
                                        <span class="d-none d-sm-inline">{{ isset($mode) && $mode === 'edit' ? 'Update Profile' : 'Complete Profile' }}</span>
                                        <span class="d-inline d-sm-none">{{ isset($mode) && $mode === 'edit' ? 'Update' : 'Complete' }}</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
    @include('profile.partials.profile-form-styles')
@endsection

@section('scripts')
    @include('profile.partials.profile-form-scripts')
@endsection
