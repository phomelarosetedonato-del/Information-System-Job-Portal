@extends('layouts.app')

@section('title', $resume->full_name . ' - Resume')

@section('styles')
<style>
    .resume-view {
        background: #f5f5f5;
        min-height: 100vh;
        padding: 30px 0 10px 0;
    }

    .resume-container {
        max-width: 900px;
        margin: 0 auto;
        background: white;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
    }

    .resume-actions {
        background: white;
        padding: 10px 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        position: sticky;
        top: 80px;
        z-index: 100;
        margin-bottom: 10px;
    }

    .resume-header {
        background: linear-gradient(90deg, #1A5D34 0%, #2E8B57 100%);
        color: white;
        padding: 20px 30px;
        text-align: center;
    }

    .resume-header h1 {
        font-size: 1.75rem;
        margin-bottom: 10px;
    }

    .resume-header p {
        font-size: 0.9rem;
        margin-bottom: 5px;
    }

    .profile-photo-display {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        border: 3px solid white;
        object-fit: cover;
        margin-bottom: 10px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    }

    .resume-body {
        padding: 25px 35px;
    }

    .section-header {
        color: #1A5D34;
        font-weight: 600;
        font-size: 1.2rem;
        border-bottom: 2px solid #2E8B57;
        padding-bottom: 8px;
        margin-top: 20px;
        margin-bottom: 15px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
        margin-bottom: 20px;
    }

    .info-item {
        display: flex;
        flex-direction: column;
    }

    .info-label {
        font-weight: 600;
        color: #666;
        font-size: 0.85rem;
        margin-bottom: 5px;
    }

    .info-value {
        color: #333;
        font-size: 1rem;
    }

    .experience-item, .education-item, .training-item {
        border-left: 3px solid #2E8B57;
        padding-left: 20px;
        margin-bottom: 20px;
    }

    .experience-title {
        font-weight: 600;
        color: #1A5D34;
        font-size: 1.1rem;
    }

    .experience-company {
        color: #666;
        font-size: 0.95rem;
        margin-bottom: 5px;
    }

    .experience-date {
        color: #999;
        font-size: 0.85rem;
        margin-bottom: 10px;
    }

    .skill-badge {
        display: inline-block;
        background: #e8f5e9;
        color: #1A5D34;
        padding: 8px 15px;
        border-radius: 20px;
        margin: 5px;
        font-size: 0.9rem;
        border: 1px solid #c8e6c9;
    }

    .document-link {
        display: inline-flex;
        align-items: center;
        padding: 10px 15px;
        background: #f5f5f5;
        border: 1px solid #ddd;
        border-radius: 4px;
        text-decoration: none;
        color: #333;
        margin: 5px;
        transition: all 0.3s;
    }

    .document-link:hover {
        background: #e8f5e9;
        border-color: #2E8B57;
        color: #1A5D34;
    }

    .status-badge {
        padding: 8px 15px;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
    }

    .badge-published {
        background: #d4edda;
        color: #155724;
    }

    .badge-draft {
        background: #fff3cd;
        color: #856404;
    }

    @media print {
        .resume-actions, .btn, .no-print {
            display: none !important;
        }

        .resume-container {
            box-shadow: none;
        }

        .resume-view {
            padding: 0;
        }
    }

    @media (max-width: 768px) {
        .resume-view {
            padding: 5px 0;
        }

        .resume-body {
            padding: 15px 10px;
        }

        .resume-header {
            padding: 15px 10px;
        }

        .resume-header h1 {
            font-size: 1.3rem;
        }

        .profile-photo-display {
            width: 80px;
            height: 80px;
        }

        .info-grid {
            grid-template-columns: 1fr;
            gap: 10px;
        }

        .section-header {
            font-size: 1rem;
            margin-top: 15px;
            margin-bottom: 10px;
        }        .experience-item, .education-item, .training-item {
            padding-left: 15px;
        }

        .skill-badge {
            font-size: 0.85rem;
            padding: 6px 12px;
        }
    }
</style>
@endsection

@section('content')
<div class="resume-view">
    <div class="container-fluid">
        <!-- Action Bar -->
        <div class="resume-actions no-print">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('resumes.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-2"></i>Back to Resumes
                    </a>

                    @can('update', $resume)
                        <span class="status-badge {{ $resume->is_published ? 'badge-published' : 'badge-draft' }}">
                            <i class="fas fa-{{ $resume->is_published ? 'check-circle' : 'clock' }} me-1"></i>
                            {{ $resume->is_published ? 'Published' : 'Draft' }}
                        </span>

                        <span class="badge bg-info">
                            <i class="fas fa-chart-line me-1"></i>{{ $resume->completion_percentage }}% Complete
                        </span>
                    @endcan

                    @if(!$isOwner)
                        <span class="badge bg-secondary">
                            <i class="fas fa-eye me-1"></i>{{ $resume->views_count }} views
                        </span>
                    @endif
                </div>

                <div class="d-flex gap-2">
                    @can('update', $resume)
                        <a href="{{ route('resumes.edit', $resume) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-edit me-2"></i>Edit
                        </a>

                        <form action="{{ route('resumes.toggle-publish', $resume) }}" method="POST" class="d-inline">
                            @csrf
                            @if($resume->is_published)
                                <button type="submit" class="btn btn-outline-warning btn-sm">
                                    <i class="fas fa-eye-slash me-2"></i>Unpublish
                                </button>
                            @else
                                <button type="submit" class="btn btn-success btn-sm" {{ $resume->canBePublished() ? '' : 'disabled' }}>
                                    <i class="fas fa-globe me-2"></i>Publish
                                </button>
                            @endif
                        </form>
                    @endcan

                    <button onclick="window.print()" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-print me-2"></i>Print
                    </button>

                    <a href="{{ route('resumes.download', $resume) }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-download me-2"></i>Download PDF
                    </a>
                </div>
            </div>
        </div>

        <!-- Resume Content -->
        <div class="resume-container">
            <!-- Header -->
            <div class="resume-header">
                @if($resume->profile_photo)
                    <img src="{{ $resume->profile_photo_url }}" alt="Profile Photo" class="profile-photo-display">
                @endif
                <h1 class="mb-2">{{ $resume->full_name }}</h1>
                <p class="mb-1">
                    <i class="fas fa-map-marker-alt me-2"></i>{{ $resume->province }}
                    @if($resume->complete_address)
                        <br><small>{{ $resume->complete_address }}</small>
                    @endif
                </p>
                <p class="mb-0">
                    <i class="fas fa-envelope me-3"></i>{{ $resume->email_address }}
                    <i class="fas fa-phone ms-3 me-2"></i>{{ $resume->mobile_number }}
                </p>
            </div>

            <div class="resume-body">
                <!-- Personal Information -->
                <div class="section-header">
                    <i class="fas fa-user me-2"></i>Personal Information
                </div>

                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Date of Birth</span>
                        <span class="info-value">{{ $resume->date_of_birth ? $resume->date_of_birth->format('F d, Y') : 'N/A' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Age</span>
                        <span class="info-value">{{ $resume->age }} years old</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Sex</span>
                        <span class="info-value">{{ ucfirst($resume->sex) }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Mobile Number</span>
                        <span class="info-value">{{ $resume->mobile_number }}</span>
                    </div>
                </div>

                <!-- Professional Summary -->
                @if($resume->professional_summary)
                    <div class="section-header">
                        <i class="fas fa-briefcase me-2"></i>Professional Summary
                    </div>
                    <p class="text-muted" style="line-height: 1.8;">{{ $resume->professional_summary }}</p>
                @endif

                <!-- Career Objective -->
                @if($resume->career_objective)
                    <div class="section-header">
                        <i class="fas fa-bullseye me-2"></i>Career Objective
                    </div>
                    <p class="text-muted" style="line-height: 1.8;">{{ $resume->career_objective }}</p>
                @endif

                <!-- Education -->
                <div class="section-header">
                    <i class="fas fa-graduation-cap me-2"></i>Education
                </div>

                <div class="education-item">
                    <div class="experience-title">{{ $resume->educational_attainment }}</div>
                    @if($resume->course)
                        <div class="experience-company">{{ $resume->course }}</div>
                    @endif
                    @if($resume->school_name)
                        <div class="experience-company">{{ $resume->school_name }}</div>
                        @if($resume->school_address)
                            <div class="text-muted small">{{ $resume->school_address }}</div>
                        @endif
                    @endif
                    @if($resume->year_graduated)
                        <div class="experience-date">Graduated: {{ $resume->year_graduated }}</div>
                    @endif
                </div>

                @if($resume->additional_education && count($resume->additional_education) > 0)
                    @foreach($resume->additional_education as $edu)
                        <div class="education-item">
                            <div class="experience-title">{{ $edu['degree'] ?? 'N/A' }}</div>
                            <div class="experience-company">{{ $edu['school'] ?? 'N/A' }}</div>
                            @if(isset($edu['year']))
                                <div class="experience-date">{{ $edu['year'] }}</div>
                            @endif
                        </div>
                    @endforeach
                @endif

                <!-- Work Experience -->
                @if($resume->work_experience && count($resume->work_experience) > 0)
                    <div class="section-header">
                        <i class="fas fa-building me-2"></i>Work Experience
                    </div>

                    @foreach($resume->work_experience as $work)
                        <div class="experience-item">
                            <div class="experience-title">{{ $work['position'] ?? 'N/A' }}</div>
                            <div class="experience-company">{{ $work['company'] ?? 'N/A' }}</div>
                            @if(isset($work['start_date']) || isset($work['end_date']))
                                <div class="experience-date">
                                    {{ $work['start_date'] ?? 'N/A' }} - {{ $work['end_date'] ?? 'Present' }}
                                </div>
                            @endif
                            @if(isset($work['description']))
                                <p class="text-muted mt-2">{{ $work['description'] }}</p>
                            @endif
                        </div>
                    @endforeach
                @endif

                <!-- Skills -->
                @if($resume->skills && count($resume->skills) > 0)
                    <div class="section-header">
                        <i class="fas fa-tools me-2"></i>Skills & Competencies
                    </div>

                    <div class="mb-3">
                        @foreach($resume->skills as $skill)
                            <span class="skill-badge">
                                {{ $skill['name'] ?? 'N/A' }}
                                @if(isset($skill['level']))
                                    <small class="text-muted">- {{ ucfirst($skill['level']) }}</small>
                                @endif
                            </span>
                        @endforeach
                    </div>
                @endif

                <!-- Languages -->
                @if($resume->languages && count($resume->languages) > 0)
                    <div class="section-header">
                        <i class="fas fa-language me-2"></i>Languages
                    </div>

                    <div class="mb-3">
                        @foreach($resume->languages as $language)
                            <span class="skill-badge">
                                {{ $language['name'] ?? 'N/A' }}
                                @if(isset($language['proficiency']))
                                    <small class="text-muted">- {{ ucfirst($language['proficiency']) }}</small>
                                @endif
                            </span>
                        @endforeach
                    </div>
                @endif

                <!-- Eligibility/Certifications -->
                @if($resume->eligibility && count($resume->eligibility) > 0)
                    <div class="section-header">
                        <i class="fas fa-certificate me-2"></i>Certifications & Eligibility
                    </div>

                    @foreach($resume->eligibility as $cert)
                        <div class="education-item">
                            <div class="experience-title">{{ $cert['title'] ?? 'N/A' }}</div>
                            @if(isset($cert['year']))
                                <div class="experience-date">{{ $cert['year'] }}</div>
                            @endif
                        </div>
                    @endforeach
                @endif

                <!-- Training & Seminars -->
                @if($resume->trainings && count($resume->trainings) > 0)
                    <div class="section-header">
                        <i class="fas fa-chalkboard-teacher me-2"></i>Training & Seminars
                    </div>

                    @foreach($resume->trainings as $training)
                        <div class="training-item">
                            <div class="experience-title">{{ $training['title'] ?? 'N/A' }}</div>
                            @if(isset($training['organizer']))
                                <div class="experience-company">{{ $training['organizer'] }}</div>
                            @endif
                            @if(isset($training['date_from']) || isset($training['date_to']))
                                <div class="experience-date">
                                    {{ $training['date_from'] ?? 'N/A' }} - {{ $training['date_to'] ?? 'N/A' }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                @endif

                <!-- Application Letter -->
                @if($resume->application_letter)
                    <div class="section-header">
                        <i class="fas fa-envelope me-2"></i>Application Letter
                    </div>
                    <p class="text-muted" style="line-height: 1.8; white-space: pre-wrap;">{{ $resume->application_letter }}</p>
                @endif

                <!-- Documents -->
                @can('view-documents', $resume)
                    @if(($resume->personal_documents && count($resume->personal_documents) > 0) || ($resume->supporting_documents && count($resume->supporting_documents) > 0))
                        <div class="section-header no-print">
                            <i class="fas fa-file-pdf me-2"></i>Attached Documents
                        </div>

                        @if($resume->personal_documents && count($resume->personal_documents) > 0)
                            <div class="mb-3">
                                <strong class="d-block mb-2">Personal Documents:</strong>
                                @foreach($resume->personal_documents as $doc)
                                    <a href="{{ Storage::url($doc) }}" target="_blank" class="document-link">
                                        <i class="fas fa-file-pdf me-2"></i>
                                        {{ basename($doc) }}
                                    </a>
                                @endforeach
                            </div>
                        @endif

                        @if($resume->supporting_documents && count($resume->supporting_documents) > 0)
                            <div class="mb-3">
                                <strong class="d-block mb-2">Supporting Documents:</strong>
                                @foreach($resume->supporting_documents as $doc)
                                    <a href="{{ Storage::url($doc) }}" target="_blank" class="document-link">
                                        <i class="fas fa-file-pdf me-2"></i>
                                        {{ basename($doc) }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    @endif
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection
