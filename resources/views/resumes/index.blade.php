@extends('layouts.app')

@section('title', 'My Resumes')

@section('styles')
<style>
    .resumes-page {
        background: #f5f5f5;
        min-height: 100vh;
        padding: 30px 0;
    }

    .page-header {
        background: white;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 30px;
    }

    .resume-card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        padding: 25px;
        margin-bottom: 20px;
        transition: all 0.3s;
    }

    .resume-card:hover {
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        transform: translateY(-2px);
    }

    .resume-card-header {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }

    .resume-photo {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #2E8B57;
        margin-right: 20px;
    }

    .resume-info h4 {
        color: #1A5D34;
        margin-bottom: 5px;
        font-weight: 600;
    }

    .resume-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 15px;
    }

    .meta-badge {
        display: inline-flex;
        align-items: center;
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .badge-published {
        background: #d4edda;
        color: #155724;
    }

    .badge-draft {
        background: #fff3cd;
        color: #856404;
    }

    .badge-completion {
        background: #cfe2ff;
        color: #084298;
    }

    .badge-views {
        background: #f8f9fa;
        color: #6c757d;
    }

    .resume-summary {
        color: #666;
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 15px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .resume-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .btn-resume-action {
        padding: 8px 16px;
        font-size: 0.9rem;
        border-radius: 4px;
    }

    .empty-state {
        background: white;
        padding: 60px 30px;
        text-align: center;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .empty-icon {
        font-size: 4rem;
        color: #ddd;
        margin-bottom: 20px;
    }

    .progress-ring {
        transform: rotate(-90deg);
    }

    .progress-text {
        font-size: 1.2rem;
        font-weight: 600;
        fill: #1A5D34;
    }

    @media (max-width: 768px) {
        .resumes-page {
            padding: 15px 0;
        }

        .page-header {
            padding: 20px 15px;
        }

        .resume-card {
            padding: 20px 15px;
        }

        .resume-card-header {
            flex-direction: column;
            text-align: center;
        }

        .resume-photo {
            margin-right: 0;
            margin-bottom: 15px;
        }

        .resume-meta {
            justify-content: center;
        }

        .resume-actions {
            justify-content: center;
        }

        .btn-resume-action {
            font-size: 0.85rem;
            padding: 6px 12px;
        }
    }
</style>
@endsection

@section('content')
<div class="resumes-page">
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                <div>
                    <h2 class="mb-2"><i class="fas fa-file-alt me-2"></i>My Resumes</h2>
                    <p class="text-muted mb-0">Manage your professional resumes and application documents</p>
                </div>
                <a href="{{ route('resumes.create') }}" class="btn btn-add-more">
                    <i class="fas fa-plus me-2"></i>Create New Resume
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Resumes List -->
        @if($resumes->count() > 0)
            @foreach($resumes as $resume)
                <div class="resume-card">
                    <div class="resume-card-header">
                        <img src="{{ $resume->profile_photo_url }}" alt="Profile Photo" class="resume-photo">
                        <div class="resume-info flex-grow-1">
                            <h4>{{ $resume->full_name }}</h4>
                            <p class="text-muted mb-0">
                                <i class="fas fa-map-marker-alt me-2"></i>{{ $resume->province }}
                                @if($resume->date_of_birth)
                                    <i class="fas fa-birthday-cake ms-3 me-2"></i>{{ $resume->age }} years old
                                @endif
                            </p>
                        </div>
                        <div class="completion-indicator">
                            <svg width="60" height="60" viewBox="0 0 60 60" class="progress-ring">
                                <circle cx="30" cy="30" r="25" fill="none" stroke="#e0e0e0" stroke-width="5"/>
                                <circle cx="30" cy="30" r="25" fill="none"
                                    stroke="{{ $resume->completion_percentage >= 80 ? '#28a745' : '#ffc107' }}"
                                    stroke-width="5"
                                    stroke-dasharray="{{ $resume->completion_percentage * 1.57 }} 157"
                                    stroke-linecap="round"/>
                                <text x="30" y="35" text-anchor="middle" class="progress-text" font-size="14">
                                    {{ $resume->completion_percentage }}%
                                </text>
                            </svg>
                        </div>
                    </div>

                    <div class="resume-meta">
                        <span class="meta-badge {{ $resume->is_published ? 'badge-published' : 'badge-draft' }}">
                            <i class="fas fa-{{ $resume->is_published ? 'check-circle' : 'clock' }} me-1"></i>
                            {{ $resume->is_published ? 'Published' : 'Draft' }}
                        </span>

                        <span class="meta-badge badge-completion">
                            <i class="fas fa-tasks me-1"></i>
                            {{ $resume->completion_percentage }}% Complete
                        </span>

                        <span class="meta-badge badge-views">
                            <i class="fas fa-eye me-1"></i>
                            {{ $resume->views_count }} {{ Str::plural('view', $resume->views_count) }}
                        </span>

                        @if($resume->last_updated_at)
                            <span class="meta-badge" style="background: #f8f9fa; color: #6c757d;">
                                <i class="fas fa-clock me-1"></i>
                                Updated {{ $resume->last_updated_at->diffForHumans() }}
                            </span>
                        @endif
                    </div>

                    @if($resume->professional_summary)
                        <div class="resume-summary">
                            {{ $resume->professional_summary }}
                        </div>
                    @endif

                    @if($resume->educational_attainment || $resume->course)
                        <div class="mb-3">
                            <i class="fas fa-graduation-cap me-2 text-muted"></i>
                            <span class="text-muted">
                                {{ $resume->educational_attainment }}
                                @if($resume->course)
                                    - {{ $resume->course }}
                                @endif
                            </span>
                        </div>
                    @endif

                    @if($resume->work_experience && count($resume->work_experience) > 0)
                        <div class="mb-3">
                            <i class="fas fa-briefcase me-2 text-muted"></i>
                            <span class="text-muted">
                                {{ count($resume->work_experience) }} work {{ Str::plural('experience', count($resume->work_experience)) }}
                            </span>
                        </div>
                    @endif

                    <div class="resume-actions">
                        <a href="{{ route('resumes.show', $resume) }}" class="btn btn-outline-primary btn-resume-action">
                            <i class="fas fa-eye me-1"></i>View
                        </a>

                        <a href="{{ route('resumes.edit', $resume) }}" class="btn btn-outline-secondary btn-resume-action">
                            <i class="fas fa-edit me-1"></i>Edit
                        </a>

                        <form action="{{ route('resumes.toggle-publish', $resume) }}" method="POST" class="d-inline">
                            @csrf
                            @if($resume->is_published)
                                <button type="submit" class="btn btn-outline-warning btn-resume-action">
                                    <i class="fas fa-eye-slash me-1"></i>Unpublish
                                </button>
                            @else
                                <button type="submit" class="btn btn-outline-success btn-resume-action"
                                    {{ $resume->canBePublished() ? '' : 'disabled' }}
                                    title="{{ $resume->canBePublished() ? 'Publish resume' : 'Complete 80% to publish' }}">
                                    <i class="fas fa-globe me-1"></i>Publish
                                </button>
                            @endif
                        </form>

                        <a href="{{ route('resumes.download', $resume) }}" class="btn btn-outline-info btn-resume-action">
                            <i class="fas fa-download me-1"></i>Download
                        </a>

                        <button type="button" class="btn btn-outline-danger btn-resume-action"
                            onclick="confirmDelete('{{ $resume->id }}', '{{ $resume->full_name }}')">
                            <i class="fas fa-trash me-1"></i>Delete
                        </button>
                    </div>
                </div>
            @endforeach

            <!-- Pagination -->
            @if($resumes->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $resumes->links() }}
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <h3 class="mb-3">No Resumes Yet</h3>
                <p class="text-muted mb-4">Create your first professional resume to get started with job applications</p>
                <a href="{{ route('resumes.create') }}" class="btn btn-add-more btn-lg">
                    <i class="fas fa-plus me-2"></i>Create Your First Resume
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Confirm Deletion</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">Are you sure you want to delete the resume for <strong id="resumeName"></strong>?</p>
                <p class="text-danger mt-2 mb-0"><small>This action cannot be undone.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>Delete Resume
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<style>
    .btn-add-more {
        background: linear-gradient(90deg, #1A5D34 0%, #2E8B57 100%);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 4px;
        font-size: 1rem;
        font-weight: 500;
        transition: all 0.3s;
    }

    .btn-add-more:hover {
        background: linear-gradient(90deg, #145028 0%, #257a47 100%);
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(26, 93, 52, 0.3);
    }
</style>

<script>
function confirmDelete(resumeId, resumeName) {
    document.getElementById('resumeName').textContent = resumeName;
    document.getElementById('deleteForm').action = `/resumes/${resumeId}`;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endsection
