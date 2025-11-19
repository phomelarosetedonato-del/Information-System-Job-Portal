<!-- Resume Required Modal -->
<div class="modal fade" id="resumeRequiredModal" tabindex="-1" aria-labelledby="resumeRequiredModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header text-white py-2 px-3" style="background: linear-gradient(90deg, #1A5D34 0%, #2E8B57 100%);">
                <h6 class="modal-title mb-0" id="resumeRequiredModalLabel" style="font-size: 0.95rem;">
                    <i class="fas fa-file-upload me-1"></i>Resume Required to Apply
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="font-size: 0.8rem;"></button>
            </div>
            <div class="modal-body p-2 p-md-3">
                <div class="row">
                    <div class="col-md-6 text-center mb-3 mb-md-0">
                        <i class="fas fa-file-alt fa-2x mb-2" style="color: #2E8B57;"></i>
                        <h5 class="text-dark mb-2" style="font-size: 1rem;">Ready to Apply?</h5>
                        <p class="text-muted mb-0" style="font-size: 0.85rem;">Create your professional resume to start applying for jobs and showcase your skills to employers.</p>
                    </div>
                    <div class="col-md-6">
                        <!-- Quick Eligibility Check -->
                        @php
                            $user = auth()->user();
                            $hasResume = $user->hasResume(); // Check both PDF and database resumes
                            $eligibility = $user->getJobApplicationEligibility();
                        @endphp

                        <div class="mb-2">
                            <h6 class="text-dark mb-2 fw-bold" style="font-size: 0.85rem;">Application Requirements:</h6>
                            <div class="d-flex align-items-center mb-1" style="font-size: 0.85rem;">
                                <span class="badge bg-{{ $hasResume ? 'success' : 'warning' }} me-2">
                                    <i class="fas fa-{{ $hasResume ? 'check' : 'exclamation' }}"></i>
                                </span>
                                <span style="font-size: 0.85rem;">Resume Created</span>
                            </div>
                            <div class="d-flex align-items-center mb-1" style="font-size: 0.85rem;">
                                <span class="badge bg-{{ $user->hasCompletePwdProfile() ? 'success' : 'warning' }} me-2">
                                    <i class="fas fa-{{ $user->hasCompletePwdProfile() ? 'check' : 'exclamation' }}"></i>
                                </span>
                                <span style="font-size: 0.85rem;">PWD Profile Complete</span>
                            </div>
                            <div class="d-flex align-items-center" style="font-size: 0.85rem;">
                                <span class="badge bg-{{ $user->isPwd() ? 'success' : 'danger' }} me-2">
                                    <i class="fas fa-{{ $user->isPwd() ? 'check' : 'times' }}"></i>
                                </span>
                                <span style="font-size: 0.85rem;">PWD User Account</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Two Resume Options -->
                <div class="row mt-2">
                    <!-- Option 1: Quick Upload PDF -->
                    <div class="col-md-6 mb-2">
                        <div class="card border-0 h-100" style="box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.15); border-left: 4px solid #2E8B57 !important;">
                            <div class="card-body text-center py-2">
                                <i class="fas fa-file-upload fa-lg mb-1" style="color: #2E8B57;"></i>
                                <h6 class="card-title fw-bold mb-1" style="font-size: 0.9rem;">Upload Resume PDF</h6>
                                <p class="card-text text-muted mb-2" style="font-size: 0.8rem;">Already have a resume? Upload your PDF file directly</p>
                                <button type="button" class="btn btn-sm w-100 text-white" style="background: linear-gradient(90deg, #1A5D34 0%, #2E8B57 100%); border: none;" data-bs-toggle="modal" data-bs-target="#quickUploadModal" data-bs-dismiss="modal">
                                    <i class="fas fa-upload me-1"></i> Upload PDF
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Option 2: Build Resume Form -->
                    <div class="col-md-6 mb-2">
                        <div class="card border-0 h-100" style="box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.15); border-left: 4px solid #4A90E2 !important;">
                            <div class="card-body text-center py-2">
                                <i class="fas fa-edit fa-lg mb-1" style="color: #4A90E2;"></i>
                                <h6 class="card-title fw-bold mb-1" style="font-size: 0.9rem;">Build Resume Online</h6>
                                <p class="card-text text-muted mb-2" style="font-size: 0.8rem;">Create a professional resume by filling out details</p>
                                <a href="{{ route('resumes.create') }}" class="btn btn-sm w-100 text-white" style="background: linear-gradient(90deg, #3A7BC8 0%, #4A90E2 100%); border: none;">
                                    <i class="fas fa-pen-fancy me-1"></i> Build Resume
                                </a>
                            </div>
                        </div>
                    </div>

                    @if($hasResume)
                    <div class="col-12 mb-2">
                        <div class="card border-0 h-100" style="box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.15); background: #f0f8f0;">
                            <div class="card-body text-center py-2">
                                <i class="fas fa-check-circle fa-lg mb-1" style="color: #28a745;"></i>
                                <h6 class="card-title fw-bold mb-1" style="font-size: 0.9rem; color: #28a745;">Resume Already Uploaded!</h6>
                                <p class="card-text text-muted mb-2" style="font-size: 0.8rem;">You can view or update your existing resume</p>
                                <a href="{{ route('profile.show') }}" class="btn btn-outline-success btn-sm w-100">
                                    <i class="fas fa-eye me-1"></i> View My Resume
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Additional Help -->
                <div class="alert alert-light border-0 mt-2 py-2" style="background-color: #f0f8f0;">
                    <h6 class="alert-heading fw-bold mb-1" style="color: #1A5D34; font-size: 0.85rem;">
                        <i class="fas fa-lightbulb me-1"></i>Why is a resume important?
                    </h6>
                    <ul class="mb-0 text-muted" style="font-size: 0.8rem; padding-left: 1.2rem;">
                        <li>Showcases your skills and experience to employers</li>
                        <li>Increases your chances of getting hired</li>
                        <li>Helps employers understand your qualifications</li>
                        <li>Required for all job applications in our system</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer flex-column flex-sm-row py-2">
                <button type="button" class="btn btn-outline-secondary btn-sm mb-2 mb-sm-0 w-100 w-sm-auto" data-bs-dismiss="modal" style="font-size: 0.85rem; padding: 0.4rem 0.8rem;">Maybe Later</button>
                <a href="{{ route('resumes.create') }}" class="btn btn-sm w-100 w-sm-auto text-white" style="background: linear-gradient(90deg, #1A5D34 0%, #2E8B57 100%); border: none; font-size: 0.85rem; padding: 0.4rem 0.8rem;">
                    <i class="fas fa-edit me-1"></i> Create Resume
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    /* Compact Modal Styles */
    #resumeRequiredModal .modal-dialog {
        max-width: 600px;
    }

    /* Mobile Responsive Styles for Resume Modal */
    @media (max-width: 576px) {
        #resumeRequiredModal .modal-dialog {
            margin: 0.3rem;
            max-width: calc(100% - 0.6rem);
        }

        #resumeRequiredModal .modal-header {
            padding: 0.5rem 0.75rem !important;
        }

        #resumeRequiredModal .modal-title {
            font-size: 0.85rem !important;
        }

        #resumeRequiredModal .modal-body {
            padding: 0.75rem !important;
        }

        #resumeRequiredModal .fa-2x {
            font-size: 1.5rem !important;
        }

        #resumeRequiredModal .card-body {
            padding: 0.75rem !important;
        }

        #resumeRequiredModal .btn {
            padding: 0.35rem 0.7rem !important;
            font-size: 0.8rem !important;
        }

        #resumeRequiredModal .modal-footer {
            padding: 0.5rem !important;
        }

        #resumeRequiredModal ul {
            font-size: 0.75rem !important;
            padding-left: 1rem !important;
        }

        #resumeRequiredModal ul li {
            margin-bottom: 0.2rem;
        }
    }

    @media (max-width: 768px) {
        #resumeRequiredModal .modal-lg {
            max-width: calc(100% - 0.8rem);
        }
    }

    /* Quick Upload Modal Styles */
    #quickUploadModal .modal-dialog {
        max-width: 500px;
    }
</style>

<!-- Quick Upload PDF Modal -->
<div class="modal fade" id="quickUploadModal" tabindex="-1" aria-labelledby="quickUploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-white py-2 px-3" style="background: linear-gradient(90deg, #1A5D34 0%, #2E8B57 100%);">
                <h6 class="modal-title mb-0" id="quickUploadModalLabel" style="font-size: 0.95rem;">
                    <i class="fas fa-file-upload me-1"></i>Upload Resume PDF
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('profile.uploadResume') }}" method="POST" enctype="multipart/form-data" id="quickUploadForm">
                @csrf
                <div class="modal-body p-3">
                    <div class="alert alert-info py-2" style="font-size: 0.85rem;">
                        <i class="fas fa-info-circle me-1"></i>
                        <strong>Quick Upload:</strong> Upload your resume PDF (max 5MB). You'll complete your profile details after uploading.
                    </div>

                    <div class="mb-3">
                        <label for="resume_pdf" class="form-label fw-bold">Select Resume PDF File</label>
                        <input type="file" class="form-control" id="resume_pdf" name="resume" accept=".pdf" required>
                        <div class="form-text">Maximum file size: 5MB. Only PDF files are accepted.</div>
                        <div id="file-error" class="text-danger mt-2" style="display: none; font-size: 0.85rem;"></div>
                    </div>

                    <div id="file-preview" class="alert alert-success py-2" style="display: none; font-size: 0.85rem;">
                        <i class="fas fa-file-pdf me-1"></i>
                        <strong>Selected:</strong> <span id="file-name"></span>
                        <span id="file-size" class="text-muted ms-2"></span>
                    </div>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm text-white" style="background: linear-gradient(90deg, #1A5D34 0%, #2E8B57 100%); border: none;" id="uploadBtn" disabled>
                        <i class="fas fa-upload me-1"></i> Upload & Continue
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const resumeInput = document.getElementById('resume_pdf');
    const uploadBtn = document.getElementById('uploadBtn');
    const filePreview = document.getElementById('file-preview');
    const fileName = document.getElementById('file-name');
    const fileSize = document.getElementById('file-size');
    const fileError = document.getElementById('file-error');
    const form = document.getElementById('quickUploadForm');

    if (resumeInput) {
        resumeInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            fileError.style.display = 'none';

            if (file) {
                // Check file type
                if (file.type !== 'application/pdf') {
                    fileError.textContent = 'Please select a PDF file only.';
                    fileError.style.display = 'block';
                    filePreview.style.display = 'none';
                    uploadBtn.disabled = true;
                    resumeInput.value = '';
                    return;
                }

                // Check file size (5MB = 5 * 1024 * 1024 bytes)
                const maxSize = 5 * 1024 * 1024;
                if (file.size > maxSize) {
                    fileError.textContent = 'File size exceeds 5MB. Please choose a smaller file.';
                    fileError.style.display = 'block';
                    filePreview.style.display = 'none';
                    uploadBtn.disabled = true;
                    resumeInput.value = '';
                    return;
                }

                // Show preview
                fileName.textContent = file.name;
                const sizeInMB = (file.size / (1024 * 1024)).toFixed(2);
                fileSize.textContent = `(${sizeInMB} MB)`;
                filePreview.style.display = 'block';
                uploadBtn.disabled = false;
            } else {
                filePreview.style.display = 'none';
                uploadBtn.disabled = true;
            }
        });

        form.addEventListener('submit', function(e) {
            uploadBtn.disabled = true;
            uploadBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Uploading...';
        });
    }
});
</script>
