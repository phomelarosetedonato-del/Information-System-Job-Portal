@extends('employer.layouts.employer')

@section('title', 'Verification Status')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Verification Status</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        @if($canResubmit || ($status === 'Not Applied' && $profileCompletion >= 70))
            <a href="{{ route('employer.verification.apply') }}" class="btn btn-primary">
                <i class="fas fa-paper-plane"></i>
                {{ $canResubmit ? 'Resubmit Application' : 'Apply for Verification' }}
            </a>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Status Card -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Current Status</h5>
            </div>
            <div class="card-body text-center py-5">
                @if($status === 'Verified')
                    <div class="text-success mb-3">
                        <i class="fas fa-check-circle fa-5x"></i>
                    </div>
                    <h3 class="text-success">Verified Employer</h3>
                    <p class="text-muted">Your employer account has been verified successfully.</p>
                    <div class="alert alert-success">
                        <i class="fas fa-check"></i> You can now post jobs and access all employer features.
                    </div>
                    @if($user->verification_expires_at)
                        <p class="text-muted">
                            Verification expires on: <strong>{{ $user->verification_expires_at->format('F j, Y') }}</strong>
                        </p>
                    @endif
                @elseif($status === 'Pending Verification')
                    <div class="text-warning mb-3">
                        <i class="fas fa-clock fa-5x"></i>
                    </div>
                    <h3 class="text-warning">Pending Verification</h3>
                    <p class="text-muted">Your verification request is under review.</p>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        We are currently reviewing your application. This usually takes 1-2 business days.
                    </div>
                    <p class="text-muted">
                        Submitted on: <strong>{{ $user->verification_submitted_at->format('F j, Y g:i A') }}</strong>
                    </p>
                @elseif($status === 'Verification Rejected')
                    <div class="text-danger mb-3">
                        <i class="fas fa-times-circle fa-5x"></i>
                    </div>
                    <h3 class="text-danger">Verification Rejected</h3>
                    @if($user->verification_rejected_reason)
                        <div class="alert alert-danger">
                            <strong>Reason for rejection:</strong><br>
                            {{ $user->verification_rejected_reason }}
                        </div>
                    @else
                        <p class="text-muted">Your verification request was rejected.</p>
                    @endif
                    @if($canResubmit)
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            You can now resubmit your verification application.
                        </div>
                    @else
                        <p class="text-muted">
                            You can resubmit after: <strong>{{ $user->can_resubmit_verification_at->format('F j, Y') }}</strong>
                        </p>
                    @endif
                @elseif($status === 'Verification Expired')
                    <div class="text-warning mb-3">
                        <i class="fas fa-exclamation-triangle fa-5x"></i>
                    </div>
                    <h3 class="text-warning">Verification Expired</h3>
                    <p class="text-muted">Your employer verification has expired.</p>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-circle"></i>
                        Please renew your verification to continue posting jobs.
                    </div>
                    <a href="{{ route('employer.verification.renew') }}" class="btn btn-primary">
                        <i class="fas fa-sync-alt"></i> Renew Verification
                    </a>
                @else
                    <div class="text-secondary mb-3">
                        <i class="fas fa-user-shield fa-5x"></i>
                    </div>
                    <h3 class="text-secondary">Not Verified</h3>
                    <p class="text-muted">You haven't applied for employer verification yet.</p>
                    @if($profileCompletion >= 70)
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            Your profile is complete! You can now apply for verification.
                        </div>
                        <a href="{{ route('employer.verification.apply') }}" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Apply for Verification
                        </a>
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            Complete your profile ({{ $profileCompletion }}%) to apply for verification.
                        </div>
                        <a href="{{ route('employer.profile.edit') }}" class="btn btn-warning">
                            <i class="fas fa-user-edit"></i> Complete Profile
                        </a>
                    @endif
                @endif
            </div>
        </div>

        <!-- Next Steps -->
        @if($status !== 'Verified')
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Next Steps</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    @if($status === 'Not Applied')
                        <div class="col-md-4 mb-3">
                            <div class="p-3 border rounded">
                                <i class="fas fa-user-edit fa-2x text-primary mb-2"></i>
                                <h6>Complete Profile</h6>
                                <small class="text-muted">Ensure your profile is 100% complete</small>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3 border rounded">
                                <i class="fas fa-file-alt fa-2x text-primary mb-2"></i>
                                <h6>Prepare Documents</h6>
                                <small class="text-muted">Gather required verification documents</small>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3 border rounded">
                                <i class="fas fa-paper-plane fa-2x text-primary mb-2"></i>
                                <h6>Submit Application</h6>
                                <small class="text-muted">Fill out the verification form</small>
                            </div>
                        </div>
                    @elseif($status === 'Pending Verification')
                        <div class="col-md-6 mb-3">
                            <div class="p-3 border rounded">
                                <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                                <h6>Wait for Review</h6>
                                <small class="text-muted">Processing takes 1-2 business days</small>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="p-3 border rounded">
                                <i class="fas fa-envelope fa-2x text-warning mb-2"></i>
                                <h6>Check Email</h6>
                                <small class="text-muted">You'll receive notification upon completion</small>
                            </div>
                        </div>
                    @elseif($status === 'Verification Rejected')
                        <div class="col-md-6 mb-3">
                            <div class="p-3 border rounded">
                                <i class="fas fa-redo fa-2x text-danger mb-2"></i>
                                <h6>Review Feedback</h6>
                                <small class="text-muted">Address the issues mentioned above</small>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="p-3 border rounded">
                                <i class="fas fa-paper-plane fa-2x text-danger mb-2"></i>
                                <h6>Resubmit</h6>
                                <small class="text-muted">Apply again with corrected information</small>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Profile Completion -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Profile Completion</h6>
            </div>
            <div class="card-body">
                <div class="progress mb-3" style="height: 20px;">
                    <div class="progress-bar {{ $profileCompletion == 100 ? 'bg-success' : 'bg-primary' }}"
                         role="progressbar" style="width: {{ $profileCompletion }}%;">
                        {{ $profileCompletion }}%
                    </div>
                </div>
                <a href="{{ route('employer.profile.edit') }}" class="btn btn-outline-primary btn-sm w-100">
                    <i class="fas fa-edit"></i> Update Profile
                </a>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Quick Links</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('employer.verification.requirements') }}" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-list-alt"></i> View Requirements
                    </a>
                    <a href="{{ route('employer.job-drafts.create') }}" class="btn btn-outline-success btn-sm">
                        <i class="fas fa-file-alt"></i> Create Job Draft
                    </a>
                    <a href="{{ route('contact') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-question-circle"></i> Get Help
                    </a>
                </div>
            </div>
        </div>

        <!-- Benefits of Verification -->
        @if($status !== 'Verified')
        <div class="card mt-4">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0"><i class="fas fa-star"></i> Benefits</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Post unlimited jobs</li>
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Access to PWD talent pool</li>
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Advanced analytics</li>
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Verified employer badge</li>
                    <li class="mb-0"><i class="fas fa-check text-success me-2"></i> Priority support</li>
                </ul>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
