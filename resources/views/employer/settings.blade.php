@extends('employer.layouts.employer')

@section('title', 'Employer Settings')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Employer Settings</h1>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Account Settings -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Account Settings</h5>
            </div>
            <div class="card-body">
                <form>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Account Status</label>
                        <div class="col-sm-9">
                            <span class="badge bg-{{ $user->isActive() ? 'success' : 'danger' }}">
                                {{ $user->isActive() ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Member Since</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext">{{ $user->created_at->format('F j, Y') }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Last Login</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext">{{ $user->last_login_formatted }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Security Score</label>
                        <div class="col-sm-9">
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar bg-{{ $user->security_score >= 80 ? 'success' : ($user->security_score >= 60 ? 'warning' : 'danger') }}"
                                     role="progressbar" style="width: {{ $user->security_score }}%;">
                                    {{ $user->security_score }}%
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Notification Settings -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Notification Preferences</h5>
            </div>
            <div class="card-body">
                <form>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="emailNotifications" checked>
                        <label class="form-check-label" for="emailNotifications">
                            Email notifications for new applications
                        </label>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="jobAlerts" checked>
                        <label class="form-check-label" for="jobAlerts">
                            Job posting status alerts
                        </label>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="newsletter">
                        <label class="form-check-label" for="newsletter">
                            Platform updates and newsletter
                        </label>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Preferences</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Quick Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('employer.profile.edit') }}" class="btn btn-outline-primary">
                        <i class="fas fa-user-edit"></i> Edit Profile
                    </a>
                    <a href="{{ route('employer.verification.status') }}" class="btn btn-outline-info">
                        <i class="fas fa-shield-alt"></i> Verification Status
                    </a>
                    <a href="{{ route('contact') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-headset"></i> Contact Support
                    </a>
                </div>
            </div>
        </div>

        <!-- Security Recommendations -->
        <div class="card">
            <div class="card-header bg-warning">
                <h6 class="mb-0 text-white"><i class="fas fa-shield-alt"></i> Security</h6>
            </div>
            <div class="card-body">
                @php
                    $recommendations = $user->getSecurityRecommendations();
                @endphp

                @if(count($recommendations) > 0)
                    <p class="small">Recommendations to improve your account security:</p>
                    <ul class="small">
                        @foreach($recommendations as $recommendation)
                            <li>{{ $recommendation }}</li>
                        @endforeach
                    </ul>
                @else
                    <p class="small text-success">
                        <i class="fas fa-check-circle"></i> Your account security is good!
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
