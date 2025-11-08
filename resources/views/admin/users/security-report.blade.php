@extends('layouts.app')

@section('title', 'Security Report - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">Security Report</h1>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
            </div>

            <!-- Security Statistics -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h5 class="card-title">Total Users</h5>
                            <h2 class="display-4">{{ $securityStats['total_users'] ?? 0 }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h5 class="card-title">Strong Passwords</h5>
                            <h2 class="display-4">{{ $securityStats['users_with_strong_passwords'] ?? 0 }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h5 class="card-title">2FA Enabled</h5>
                            <h2 class="display-4">{{ $securityStats['users_with_2fa'] ?? 0 }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <h5 class="card-title">At Risk Users</h5>
                            <h2 class="display-4">{{ $securityStats['high_risk_users'] ?? 0 }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Security Stats -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Locked Accounts</h5>
                        </div>
                        <div class="card-body">
                            <h3 class="text-danger">{{ $securityStats['locked_accounts'] ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Expired Passwords</h5>
                        </div>
                        <div class="card-body">
                            <h3 class="text-warning">{{ $securityStats['expired_passwords'] ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Security Health</h5>
                        </div>
                        <div class="card-body">
                            @php
                                $totalUsers = $securityStats['total_users'] ?? 1;
                                $strongPasswords = $securityStats['users_with_strong_passwords'] ?? 0;
                                $with2FA = $securityStats['users_with_2fa'] ?? 0;
                                $lockedAccounts = $securityStats['locked_accounts'] ?? 0;

                                $healthScore = round(
                                    (($strongPasswords / $totalUsers) * 40) +
                                    (($with2FA / $totalUsers) * 40) +
                                    ((($totalUsers - $lockedAccounts) / $totalUsers) * 20)
                                );
                                $healthClass = $healthScore >= 80 ? 'text-success' : ($healthScore >= 60 ? 'text-warning' : 'text-danger');
                            @endphp
                            <h3 class="{{ $healthClass }}">{{ $healthScore }}%</h3>
                            <small class="text-muted">Overall Security Score</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- At-Risk Users Table -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">At-Risk Users</h5>
                </div>
                <div class="card-body">
                    @if(isset($riskUsers) && count($riskUsers) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Security Score</th>
                                        <th>Issues</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($riskUsers as $userData)
                                    @php
                                        // Handle both array and object structures
                                        $user = is_array($userData) ? $userData : $userData['user'] ?? $userData;
                                        $userId = $user['id'] ?? $user->id ?? 'N/A';
                                        $userName = $user['name'] ?? $user->name ?? 'N/A';
                                        $userEmail = $user['email'] ?? $user->email ?? 'N/A';
                                        $securityScore = $user['security_score'] ?? $user->security_score ?? $userData['security_score'] ?? 0;
                                        $isLocked = isset($user['account_locked_until']) ?
                                            ($user['account_locked_until'] && \Carbon\Carbon::parse($user['account_locked_until'])->isFuture()) :
                                            ($user->account_locked_until ?? false);
                                        $hasStrongPassword = $user['password_meets_current_standards'] ?? $user->password_meets_current_standards ?? false;
                                        $has2FA = $user['two_factor_secret'] ?? $user->two_factor_secret ?? false;
                                        $failedLogins = $user['failed_login_attempts'] ?? $user->failed_login_attempts ?? 0;

                                        // Determine issues
                                        $issues = [];
                                        if (!$hasStrongPassword) $issues[] = 'Weak Password';
                                        if (!$has2FA) $issues[] = 'No 2FA';
                                        if ($failedLogins >= 3) $issues[] = 'Multiple Failed Logins';
                                        if ($isLocked) $issues[] = 'Account Locked';

                                        $badgeClass = $securityScore >= 80 ? 'bg-success' : ($securityScore >= 60 ? 'bg-warning' : 'bg-danger');
                                    @endphp
                                    <tr>
                                        <td>{{ $userId }}</td>
                                        <td>{{ $userName }}</td>
                                        <td>{{ $userEmail }}</td>
                                        <td>
                                            <span class="badge {{ $badgeClass }}">
                                                {{ $securityScore }}%
                                            </span>
                                        </td>
                                        <td>
                                            @if(count($issues) > 0)
                                                <ul class="list-unstyled mb-0">
                                                    @foreach(array_slice($issues, 0, 3) as $issue)
                                                        <li class="small text-danger">• {{ $issue }}</li>
                                                    @endforeach
                                                    @if(count($issues) > 3)
                                                        <li class="small text-muted">• +{{ count($issues) - 3 }} more</li>
                                                    @endif
                                                </ul>
                                            @else
                                                <span class="text-muted">No issues detected</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($isLocked)
                                                <span class="badge bg-danger">Locked</span>
                                            @elseif($user['is_active'] ?? $user->is_active ?? true)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.users.show', $userId) }}" class="btn btn-sm btn-outline-primary">View</a>
                                            @if($isLocked)
                                                <form action="{{ route('admin.users.unlock', $userId) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-warning" title="Unlock Account">
                                                        Unlock
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> No at-risk users found. All users have good security practices.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Security Recommendations -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Security Recommendations</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @if(($securityStats['users_with_strong_passwords'] ?? 0) < ($securityStats['total_users'] ?? 1))
                            <li class="list-group-item">
                                <i class="fas fa-exclamation-triangle text-warning"></i>
                                Encourage users to update to stronger passwords ({{ $securityStats['total_users'] - $securityStats['users_with_strong_passwords'] }} users need to update)
                            </li>
                        @endif
                        @if(($securityStats['users_with_2fa'] ?? 0) < ($securityStats['total_users'] ?? 1))
                            <li class="list-group-item">
                                <i class="fas fa-shield-alt text-info"></i>
                                Promote two-factor authentication adoption ({{ $securityStats['total_users'] - $securityStats['users_with_2fa'] }} users without 2FA)
                            </li>
                        @endif
                        @if(($securityStats['locked_accounts'] ?? 0) > 0)
                            <li class="list-group-item">
                                <i class="fas fa-lock text-danger"></i>
                                Review and unlock {{ $securityStats['locked_accounts'] }} locked account(s) as needed
                            </li>
                        @endif
                        @if(($securityStats['expired_passwords'] ?? 0) > 0)
                            <li class="list-group-item">
                                <i class="fas fa-clock text-warning"></i>
                                Notify {{ $securityStats['expired_passwords'] }} user(s) with expired passwords to update them
                            </li>
                        @endif
                        @if(($securityStats['high_risk_users'] ?? 0) > 0)
                            <li class="list-group-item">
                                <i class="fas fa-user-shield text-danger"></i>
                                Review {{ $securityStats['high_risk_users'] }} high-risk user(s) for immediate action
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
