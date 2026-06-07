@extends('layouts.admin')

@section('title', 'Review Employer Verification - PWD System')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold">
                <i class="fas fa-user-tie text-primary"></i> Review Employer Verification
            </h2>
            <p class="text-muted">Review details and approve or reject this employer</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3">Employer Information</h5>
            <dl class="row mb-0">
                <dt class="col-sm-3">Name</dt>
                <dd class="col-sm-9">{{ $employer->name }}</dd>
                <dt class="col-sm-3">Email</dt>
                <dd class="col-sm-9">{{ $employer->email }}</dd>
                <dt class="col-sm-3">Company</dt>
                <dd class="col-sm-9">{{ $employer->company_name ?? '-' }}</dd>
                <dt class="col-sm-3">Status</dt>
                <dd class="col-sm-9">
                    <span class="badge bg-{{ $employer->employer_verification_status === 'verified' ? 'success' : ($employer->employer_verification_status === 'pending' ? 'warning' : 'danger') }}">
                        {{ ucfirst($employer->employer_verification_status ?? 'N/A') }}
                    </span>
                </dd>
                <dt class="col-sm-3">Registered At</dt>
                <dd class="col-sm-9">{{ $employer->created_at->format('Y-m-d H:i') }}</dd>
                <dt class="col-sm-3">Phone</dt>
                <dd class="col-sm-9">{{ $employer->phone ?? '-' }}</dd>
                <dt class="col-sm-3">Address</dt>
                <dd class="col-sm-9">{{ $employer->address ?? '-' }}</dd>
            </dl>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3">Admin Actions</h5>
            @if($employer->employer_verification_status === 'pending')
                <form action="{{ route('admin.employer-verifications.approve', $employer->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success me-2">Approve</button>
                </form>
                <form action="{{ route('admin.employer-verifications.reject', $employer->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger">Reject</button>
                </form>
            @else
                <div class="alert alert-info mb-0">
                    This employer has already been {{ $employer->employer_verification_status }}.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
