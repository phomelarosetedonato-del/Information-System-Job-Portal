@extends('employer.layouts.employer')

@section('title', 'Verification Requirements')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Verification Requirements</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('employer.verification.apply') }}" class="btn btn-primary">
            <i class="fas fa-paper-plane"></i> Apply Now
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Required Documents -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-file-alt"></i> Required Documents</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="text-center p-3 border rounded">
                            <i class="fas fa-file-contract fa-3x text-primary mb-3"></i>
                            <h5>Business Registration</h5>
                            <p class="text-muted">Certificate of incorporation or business registration document</p>
                            <span class="badge bg-primary">Required</span>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="text-center p-3 border rounded">
                            <i class="fas fa-receipt fa-3x text-info mb-3"></i>
                            <h5>Tax Clearance</h5>
                            <p class="text-muted">Latest tax clearance certificate</p>
                            <span class="badge bg-info">Recommended</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Process Timeline -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-list-ol"></i> Verification Process</h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item mb-4">
                        <div class="timeline-marker bg-primary"></div>
                        <div class="timeline-content">
                            <h5>1. Submit Application</h5>
                            <p class="text-muted">Complete the online form with your company information and upload required documents.</p>
                        </div>
                    </div>
                    <div class="timeline-item mb-4">
                        <div class="timeline-marker bg-info"></div>
                        <div class="timeline-content">
                            <h5>2. Document Review</h5>
                            <p class="text-muted">Our team reviews your documents and information (1-2 business days).</p>
                        </div>
                    </div>
                    <div class="timeline-item mb-4">
                        <div class="timeline-marker bg-success"></div>
                        <div class="timeline-content">
                            <h5>3. Verification Complete</h5>
                            <p class="text-muted">Once approved, you'll receive verified employer status and can post jobs.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Benefits Card -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0"><i class="fas fa-star"></i> Benefits of Verification</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li class="mb-3">
                        <i class="fas fa-check text-success me-2"></i>
                        <strong>Post Unlimited Jobs</strong>
                        <small class="d-block text-muted">Access to full job posting features</small>
                    </li>
                    <li class="mb-3">
                        <i class="fas fa-check text-success me-2"></i>
                        <strong>PWD Talent Pool</strong>
                        <small class="d-block text-muted">Connect with qualified PWD candidates</small>
                    </li>
                    <li class="mb-3">
                        <i class="fas fa-check text-success me-2"></i>
                        <strong>Verified Badge</strong>
                        <small class="d-block text-muted">Build trust with job seekers</small>
                    </li>
                    <li class="mb-3">
                        <i class="fas fa-check text-success me-2"></i>
                        <strong>Advanced Analytics</strong>
                        <small class="d-block text-muted">Track job performance and applications</small>
                    </li>
                    <li class="mb-0">
                        <i class="fas fa-check text-success me-2"></i>
                        <strong>Priority Support</strong>
                        <small class="d-block text-muted">Dedicated support team</small>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Help Card -->
        <div class="card">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0"><i class="fas fa-question-circle"></i> Need Help?</h6>
            </div>
            <div class="card-body">
                <p class="small">Contact our support team for assistance with verification:</p>
                <ul class="list-unstyled small">
                    <li><i class="fas fa-envelope me-2"></i> support@example.com</li>
                    <li><i class="fas fa-phone me-2"></i> +1 (555) 123-4567</li>
                    <li><i class="fas fa-clock me-2"></i> Mon-Fri, 9AM-5PM</li>
                </ul>
                <a href="{{ route('contact') }}" class="btn btn-outline-info btn-sm w-100">Contact Support</a>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 3rem;
}
.timeline-item {
    position: relative;
}
.timeline-marker {
    position: absolute;
    left: -3rem;
    top: 0.5rem;
    width: 2rem;
    height: 2rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
}
.timeline-content {
    margin-left: 0;
}
</style>
@endsection
