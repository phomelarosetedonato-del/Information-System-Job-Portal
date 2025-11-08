<!-- Resume Required Modal -->
<div class="modal fade" id="resumeRequiredModal" tabindex="-1" aria-labelledby="resumeRequiredModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="resumeRequiredModalLabel">
                    <i class="fas fa-file-upload me-2"></i>Resume Required to Apply
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 text-center mb-4 mb-md-0">
                        <i class="fas fa-file-alt fa-4x text-warning mb-3"></i>
                        <h4 class="text-dark">Ready to Apply?</h4>
                        <p class="text-muted">Upload your resume to start applying for jobs and showcase your skills to employers.</p>
                    </div>
                    <div class="col-md-6">
                        <!-- Quick Eligibility Check -->
                        @php
                            $user = auth()->user();
                            $eligibility = $user->getJobApplicationEligibility();
                        @endphp

                        <div class="mb-3">
                            <h6 class="text-dark mb-3">Application Requirements:</h6>
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge bg-{{ $user->hasResume() ? 'success' : 'warning' }} me-2">
                                    <i class="fas fa-{{ $user->hasResume() ? 'check' : 'exclamation' }}"></i>
                                </span>
                                <span>Resume Uploaded</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge bg-{{ $user->hasCompletePwdProfile() ? 'success' : 'warning' }} me-2">
                                    <i class="fas fa-{{ $user->hasCompletePwdProfile() ? 'check' : 'exclamation' }}"></i>
                                </span>
                                <span>PWD Profile Complete</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-{{ $user->isPwd() ? 'success' : 'danger' }} me-2">
                                    <i class="fas fa-{{ $user->isPwd() ? 'check' : 'times' }}"></i>
                                </span>
                                <span>PWD User Account</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="row mt-4">
                    <div class="col-md-6 mb-3">
                        <div class="card border-warning h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-upload fa-2x text-warning mb-3"></i>
                                <h6 class="card-title">Quick Upload</h6>
                                <p class="card-text small text-muted">Upload your existing resume file</p>
                                <form action="{{ route('profile.uploadResume') }}" method="POST" enctype="multipart/form-data" class="d-inline w-100">
                                    @csrf
                                    <input type="file" name="resume" id="modal-quick-upload" style="display: none;" onchange="this.form.submit()">
                                    <label for="modal-quick-upload" class="btn btn-warning w-100">
                                        <i class="fas fa-upload me-2"></i> Upload Resume
                                    </label>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card border-info h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-plus-circle fa-2x text-info mb-3"></i>
                                <h6 class="card-title">Create Resume</h6>
                                <p class="card-text small text-muted">Build your resume step by step</p>
                                <a href="{{ route('documents.create') }}?type=resume" class="btn btn-info w-100">
                                    <i class="fas fa-plus me-2"></i> Create Resume
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Help -->
                <div class="alert alert-info mt-3">
                    <h6 class="alert-heading">
                        <i class="fas fa-lightbulb me-2"></i>Why is a resume important?
                    </h6>
                    <ul class="mb-0 small">
                        <li>Showcases your skills and experience to employers</li>
                        <li>Increases your chances of getting hired</li>
                        <li>Helps employers understand your qualifications</li>
                        <li>Required for all job applications in our system</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Maybe Later</button>
                <a href="{{ route('profile.edit') }}#resume-section" class="btn btn-primary">
                    <i class="fas fa-cog me-2"></i> Profile Settings
                </a>
            </div>
        </div>
    </div>
</div>
