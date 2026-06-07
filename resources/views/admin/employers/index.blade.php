@extends('layouts.admin')

@section('title', 'Employer Verifications - PWD System')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold">
                <i class="fas fa-user-tie text-primary"></i> Employer Verifications
            </h2>
            <p class="text-muted">Review, approve, or reject employer registration requests</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Company</th>
                            <th>Status</th>
                            <th>Registered At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employers as $employer)
                            <tr>
                                <td>{{ $employer->name }}</td>
                                <td>{{ $employer->email }}</td>
                                <td>{{ $employer->company_name ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-{{ $employer->employer_verification_status === 'verified' ? 'success' : ($employer->employer_verification_status === 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($employer->employer_verification_status ?? 'N/A') }}
                                    </span>
                                </td>
                                <td>{{ $employer->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <a href="{{ route('admin.employer-verifications.review', $employer->id) }}" class="btn btn-sm btn-info">Review</a>
                                    @if($employer->employer_verification_status === 'pending')
                                        <form action="{{ route('admin.employer-verifications.approve', $employer->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                        </form>
                                        <form action="{{ route('admin.employer-verifications.reject', $employer->id) }}" method="POST" class="d-inline ms-1">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">No employer verification requests found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $employers->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
