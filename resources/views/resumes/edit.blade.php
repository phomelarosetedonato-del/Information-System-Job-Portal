@extends('layouts.app')

@section('title', 'Edit Resume - ' . $resume->full_name)

@section('styles')
<style>
    /* Microsoft Word-like styling */
    .resume-builder {
        background: #f0f0f0;
        min-height: 100vh;
        padding: 20px 0;
    }

    .resume-paper {
        background: white;
        max-width: 900px;
        margin: 0 auto;
        padding: 40px 60px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        min-height: 1000px;
    }

    .resume-header {
        background: linear-gradient(90deg, #1A5D34 0%, #2E8B57 100%);
        color: white;
        padding: 20px;
        margin: -40px -60px 30px;
        text-align: center;
    }

    .section-title {
        color: #1A5D34;
        font-weight: 600;
        font-size: 1.3rem;
        border-bottom: 3px solid #2E8B57;
        padding-bottom: 10px;
        margin-top: 30px;
        margin-bottom: 20px;
    }

    .form-label {
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
    }

    .form-control, .form-select {
        border: 1px solid #ddd;
        padding: 10px;
        font-size: 0.95rem;
    }

    .form-control:focus, .form-select:focus {
        border-color: #2E8B57;
        box-shadow: 0 0 0 0.2rem rgba(46, 139, 87, 0.25);
    }

    .dynamic-section {
        background: #f9f9f9;
        padding: 20px;
        border-left: 4px solid #2E8B57;
        margin-bottom: 15px;
        border-radius: 4px;
    }

    .btn-add-more {
        background: #2E8B57;
        color: white;
        border: none;
        padding: 8px 20px;
        border-radius: 4px;
        font-size: 0.9rem;
    }

    .btn-add-more:hover {
        background: #1A5D34;
        color: white;
    }

    .btn-remove {
        background: #dc3545;
        color: white;
        border: none;
        padding: 5px 15px;
        border-radius: 4px;
        font-size: 0.85rem;
    }

    .photo-preview {
        width: 150px;
        height: 150px;
        border: 2px dashed #ddd;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 10px 0;
        border-radius: 8px;
        overflow: hidden;
    }

    .photo-preview img {
        max-width: 100%;
        max-height: 100%;
        object-fit: cover;
    }

    .file-upload-label {
        display: inline-block;
        padding: 10px 20px;
        background: #2E8B57;
        color: white;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.9rem;
    }

    .file-upload-label:hover {
        background: #1A5D34;
    }

    .progress-bar-container {
        position: sticky;
        top: 70px;
        background: white;
        padding: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        z-index: 100;
        margin: -20px 0 20px;
    }

    .completion-badge {
        font-size: 1.2rem;
        padding: 8px 15px;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .resume-paper {
            padding: 20px 15px;
            margin: 0 10px;
        }

        .resume-header {
            margin: -20px -15px 20px;
            padding: 15px;
        }

        .section-title {
            font-size: 1.1rem;
        }

        .form-control, .form-select {
            font-size: 0.9rem;
        }

        .dynamic-section {
            padding: 15px;
        }

        .photo-preview {
            width: 120px;
            height: 120px;
        }
    }

    @media (max-width: 576px) {
        .resume-builder {
            padding: 10px 0;
        }

        .resume-paper {
            padding: 15px 10px;
            margin: 0 5px;
        }

        .section-title {
            font-size: 1rem;
            margin-top: 20px;
        }

        .btn-add-more, .btn-remove {
            font-size: 0.8rem;
            padding: 6px 12px;
        }
    }
</style>
@endsection

@section('content')
<div class="resume-builder">
    <div class="container-fluid">
        <!-- Progress Bar -->
        <div class="progress-bar-container">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="mb-0"><i class="fas fa-clipboard-check me-2"></i>Resume Completion</h6>
                <span class="completion-badge badge bg-success">0%</span>
            </div>
            <div class="progress" style="height: 8px;">
                <div class="progress-bar bg-success" role="progressbar" style="width: 0%" id="completionProgress"></div>
            </div>
            <small class="text-muted">Complete at least 80% to publish your resume</small>
        </div>

        <div class="resume-paper">
            <!-- Header -->
            <div class="resume-header">
                <h2 class="mb-1"><i class="fas fa-edit me-2"></i>Edit Your Professional Resume</h2>
                <p class="mb-0 small">Update your information to keep your resume current</p>
            </div>

            <form action="{{ route('resumes.update', $resume) }}" method="POST" enctype="multipart/form-data" id="resumeForm">
                @csrf
                @method('PUT')

                <!-- Personal Information Section -->
                <div class="section-title">
                    <i class="fas fa-user me-2"></i>Personal Information
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Surname <span class="text-danger">*</span></label>
                        <input type="text" name="surname" class="form-control" value="{{ old('surname', $resume->surname) }}" required>
                        @error('surname')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">First Name <span class="text-danger">*</span></label>
                        <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $resume->first_name) }}" required>
                        @error('first_name')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Middle Name</label>
                        <input type="text" name="middle_name" class="form-control" value="{{ old('middle_name', $resume->middle_name) }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                        <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', $resume->date_of_birth?->format('Y-m-d')) }}" required>
                        @error('date_of_birth')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Sex <span class="text-danger">*</span></label>
                        <select name="sex" class="form-select" required>
                            <option value="">Select...</option>
                            <option value="male" {{ old('sex', $resume->sex) == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('sex', $resume->sex) == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="prefer_not_to_say" {{ old('sex', $resume->sex) == 'prefer_not_to_say' ? 'selected' : '' }}>Prefer not to say</option>
                        </select>
                        @error('sex')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Mobile Number <span class="text-danger">*</span></label>
                        <input type="tel" name="mobile_number" class="form-control" value="{{ old('mobile_number', $resume->mobile_number) }}" placeholder="+639123456789" required>
                        @error('mobile_number')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email Address <span class="text-danger">*</span></label>
                        <input type="email" name="email_address" class="form-control" value="{{ old('email_address', $resume->email_address) }}" required>
                        @error('email_address')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Province <span class="text-danger">*</span></label>
                        <input type="text" name="province" class="form-control" value="{{ old('province', $resume->province) }}" required>
                        @error('province')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Complete Address</label>
                    <textarea name="complete_address" class="form-control" rows="2">{{ old('complete_address', $resume->complete_address) }}</textarea>
                    <small class="text-muted">House No., Street, Barangay, City, Province</small>
                </div>

                <!-- Profile Photo -->
                <div class="mb-3">
                    <label class="form-label">Profile Photo</label>
                    <div class="photo-preview" id="photoPreview">
                        @if($resume->profile_photo)
                            <img src="{{ $resume->profile_photo_url }}" alt="Profile Photo">
                        @else
                            <span class="text-muted"><i class="fas fa-camera fa-2x"></i></span>
                        @endif
                    </div>
                    <input type="file" name="profile_photo" id="profilePhoto" class="d-none" accept="image/*">
                    <label for="profilePhoto" class="file-upload-label">
                        <i class="fas fa-upload me-2"></i>Change Photo
                    </label>
                    <small class="d-block text-muted mt-2">JPG, PNG (Max 2MB)</small>
                </div>

                <!-- Professional Summary Section -->
                <div class="section-title">
                    <i class="fas fa-briefcase me-2"></i>Professional Profile
                </div>

                <div class="mb-3">
                    <label class="form-label">Professional Summary</label>
                    <textarea name="professional_summary" class="form-control" rows="4" placeholder="Brief overview of your professional background and expertise...">{{ old('professional_summary', $resume->professional_summary) }}</textarea>
                    <small class="text-muted">2-3 sentences highlighting your key strengths</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Career Objective</label>
                    <textarea name="career_objective" class="form-control" rows="3" placeholder="Your career goals and aspirations...">{{ old('career_objective', $resume->career_objective) }}</textarea>
                </div>

                <!-- Education Section -->
                <div class="section-title">
                    <i class="fas fa-graduation-cap me-2"></i>Education
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Highest Educational Attainment <span class="text-danger">*</span></label>
                        <select name="educational_attainment" class="form-select" required>
                            <option value="">Select...</option>
                            <option value="Elementary" {{ old('educational_attainment', $resume->educational_attainment) == 'Elementary' ? 'selected' : '' }}>Elementary</option>
                            <option value="High School" {{ old('educational_attainment', $resume->educational_attainment) == 'High School' ? 'selected' : '' }}>High School</option>
                            <option value="Senior High School" {{ old('educational_attainment', $resume->educational_attainment) == 'Senior High School' ? 'selected' : '' }}>Senior High School</option>
                            <option value="Vocational/Technical" {{ old('educational_attainment', $resume->educational_attainment) == 'Vocational/Technical' ? 'selected' : '' }}>Vocational/Technical</option>
                            <option value="Associate Degree" {{ old('educational_attainment', $resume->educational_attainment) == 'Associate Degree' ? 'selected' : '' }}>Associate Degree</option>
                            <option value="Bachelor's Degree" {{ old('educational_attainment', $resume->educational_attainment) == "Bachelor's Degree" ? 'selected' : '' }}>Bachelor's Degree</option>
                            <option value="Master's Degree" {{ old('educational_attainment', $resume->educational_attainment) == "Master's Degree" ? 'selected' : '' }}>Master's Degree</option>
                            <option value="Doctorate" {{ old('educational_attainment', $resume->educational_attainment) == 'Doctorate' ? 'selected' : '' }}>Doctorate</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Course/Degree</label>
                        <input type="text" name="course" class="form-control" value="{{ old('course', $resume->course) }}" placeholder="e.g., BS Computer Science">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label class="form-label">School Name</label>
                        <input type="text" name="school_name" class="form-control" value="{{ old('school_name', $resume->school_name) }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Year Graduated</label>
                        <input type="number" name="year_graduated" class="form-control" value="{{ old('year_graduated', $resume->year_graduated) }}" min="1950" max="{{ date('Y') + 10 }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">School Address</label>
                    <input type="text" name="school_address" class="form-control" value="{{ old('school_address', $resume->school_address) }}">
                </div>

                <!-- Additional Education (Dynamic) -->
                <div class="mb-3">
                    <label class="form-label">Additional Education/Training</label>
                    <div id="additionalEducationContainer"></div>
                    <button type="button" class="btn-add-more" onclick="addEducation()">
                        <i class="fas fa-plus me-1"></i>Add Education
                    </button>
                </div>

                <!-- Work Experience Section -->
                <div class="section-title">
                    <i class="fas fa-building me-2"></i>Work Experience
                </div>

                <div id="workExperienceContainer">
                    <!-- Will be populated by JavaScript -->
                </div>
                <button type="button" class="btn-add-more" onclick="addWorkExperience()">
                    <i class="fas fa-plus me-1"></i>Add Work Experience
                </button>

                <!-- Skills Section -->
                <div class="section-title mt-4">
                    <i class="fas fa-tools me-2"></i>Skills & Competencies
                </div>

                <div class="mb-3">
                    <label class="form-label">Skills</label>
                    <div id="skillsContainer"></div>
                    <button type="button" class="btn-add-more" onclick="addSkill()">
                        <i class="fas fa-plus me-1"></i>Add Skill
                    </button>
                    <small class="d-block text-muted mt-2">Add your technical and soft skills</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Languages</label>
                    <div id="languagesContainer"></div>
                    <button type="button" class="btn-add-more" onclick="addLanguage()">
                        <i class="fas fa-plus me-1"></i>Add Language
                    </button>
                </div>

                <!-- Eligibility/Certifications Section -->
                <div class="section-title">
                    <i class="fas fa-certificate me-2"></i>Eligibility & Certifications
                </div>

                <div id="eligibilityContainer"></div>
                <button type="button" class="btn-add-more" onclick="addEligibility()">
                    <i class="fas fa-plus me-1"></i>Add Certification
                </button>

                <!-- Training/Seminars Section -->
                <div class="section-title mt-4">
                    <i class="fas fa-chalkboard-teacher me-2"></i>Training & Seminars
                </div>

                <div id="trainingsContainer"></div>
                <button type="button" class="btn-add-more" onclick="addTraining()">
                    <i class="fas fa-plus me-1"></i>Add Training
                </button>

                <!-- Documents Section -->
                <div class="section-title mt-4">
                    <i class="fas fa-file-pdf me-2"></i>Documents
                </div>

                <div class="mb-3">
                    <label class="form-label">Personal Documents (PDF)</label>
                    <input type="file" name="personal_documents[]" class="form-control" accept=".pdf" multiple>
                    <small class="text-muted">Birth Certificate, Valid IDs, etc. (Max 5MB per file)</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Supporting Documents (PDF)</label>
                    <input type="file" name="supporting_documents[]" class="form-control" accept=".pdf" multiple>
                    <small class="text-muted">Certificates, Diplomas, Transcripts (Max 5MB per file)</small>
                </div>

                <!-- Application Letter Section -->
                <div class="section-title mt-4">
                    <i class="fas fa-envelope me-2"></i>Application Letter
                </div>

                <div class="mb-3">
                    <label class="form-label">Application Letter</label>
                    <textarea name="application_letter" class="form-control" rows="8" placeholder="Dear Hiring Manager,&#10;&#10;I am writing to express my interest in...">{{ old('application_letter', $resume->application_letter) }}</textarea>
                    <small class="text-muted">Write a compelling application letter for potential employers</small>
                </div>

                <!-- Publishing Options Section -->
                <div class="section-title mt-4">
                    <i class="fas fa-globe me-2"></i>Publishing Options
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Visibility</label>
                        <select name="visibility" class="form-select">
                            <option value="private" {{ old('visibility', $resume->visibility) == 'private' ? 'selected' : '' }}>Private (Only me)</option>
                            <option value="employers_only" {{ old('visibility', $resume->visibility) == 'employers_only' ? 'selected' : '' }}>Employers Only</option>
                            <option value="public" {{ old('visibility', $resume->visibility) == 'public' ? 'selected' : '' }}>Public</option>
                        </select>
                        <small class="text-muted">Control who can view your resume</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label d-block">Status</label>
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" name="is_searchable" id="isSearchable" value="1" {{ old('is_searchable', $resume->is_searchable) ? 'checked' : '' }}>
                            <label class="form-check-label" for="isSearchable">
                                Make my resume searchable by employers
                            </label>
                        </div>
                        <small class="text-muted d-block">Employers can find you in search results</small>
                    </div>
                </div>

                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Note:</strong> You can publish your resume after completing at least 80% of the required fields. Save as draft now and publish later.
                </div>

                <!-- Action Buttons -->
                <div class="d-flex flex-column flex-md-row gap-2 justify-content-between mt-4 mb-4">
                    <div class="d-flex gap-2">
                        <button type="submit" name="action" value="save_draft" class="btn btn-outline-secondary">
                            <i class="fas fa-save me-2"></i>Save Changes
                        </button>
                        <button type="submit" name="action" value="save_and_publish" class="btn btn-add-more">
                            <i class="fas fa-check-circle me-2"></i>Save & Publish
                        </button>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('resumes.show', $resume) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-eye me-2"></i>View Resume
                        </a>
                        <a href="{{ route('resumes.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<!-- Hidden Template for Dynamic Sections -->
<template id="workExperienceTemplate">
    <div class="dynamic-section work-experience-item">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <h6 class="mb-0"><i class="fas fa-briefcase me-2"></i>Work Experience</h6>
            <button type="button" class="btn-remove" onclick="removeSection(this)">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        <div class="row">
            <div class="col-md-6 mb-2">
                <input type="text" name="work_experience[__INDEX__][company]" class="form-control" placeholder="Company Name" required>
            </div>
            <div class="col-md-6 mb-2">
                <input type="text" name="work_experience[__INDEX__][position]" class="form-control" placeholder="Position/Title" required>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-2">
                <input type="date" name="work_experience[__INDEX__][start_date]" class="form-control" placeholder="Start Date">
            </div>
            <div class="col-md-6 mb-2">
                <input type="date" name="work_experience[__INDEX__][end_date]" class="form-control" placeholder="End Date">
            </div>
        </div>
        <textarea name="work_experience[__INDEX__][description]" class="form-control" rows="2" placeholder="Job responsibilities and achievements..."></textarea>
    </div>
</template>

<template id="educationTemplate">
    <div class="dynamic-section education-item">
        <div class="d-flex justify-content-between align-items-start mb-2">
            <h6 class="mb-0"><i class="fas fa-school me-2"></i>Education</h6>
            <button type="button" class="btn-remove" onclick="removeSection(this)">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        <div class="row">
            <div class="col-md-6 mb-2">
                <input type="text" name="additional_education[__INDEX__][school]" class="form-control" placeholder="School Name">
            </div>
            <div class="col-md-3 mb-2">
                <input type="text" name="additional_education[__INDEX__][degree]" class="form-control" placeholder="Degree">
            </div>
            <div class="col-md-3 mb-2">
                <input type="number" name="additional_education[__INDEX__][year]" class="form-control" placeholder="Year">
            </div>
        </div>
    </div>
</template>

<template id="skillTemplate">
    <div class="dynamic-section skill-item">
        <div class="row align-items-center">
            <div class="col-md-6 mb-2">
                <input type="text" name="skills[__INDEX__][name]" class="form-control" placeholder="Skill Name" required>
            </div>
            <div class="col-md-4 mb-2">
                <select name="skills[__INDEX__][level]" class="form-select">
                    <option value="beginner">Beginner</option>
                    <option value="intermediate">Intermediate</option>
                    <option value="advanced">Advanced</option>
                    <option value="expert">Expert</option>
                </select>
            </div>
            <div class="col-md-2 mb-2">
                <button type="button" class="btn-remove w-100" onclick="removeSection(this)">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    </div>
</template>

<template id="languageTemplate">
    <div class="dynamic-section language-item">
        <div class="row align-items-center">
            <div class="col-md-6 mb-2">
                <input type="text" name="languages[__INDEX__][name]" class="form-control" placeholder="Language" required>
            </div>
            <div class="col-md-4 mb-2">
                <select name="languages[__INDEX__][proficiency]" class="form-select">
                    <option value="basic">Basic</option>
                    <option value="conversational">Conversational</option>
                    <option value="fluent">Fluent</option>
                    <option value="native">Native</option>
                </select>
            </div>
            <div class="col-md-2 mb-2">
                <button type="button" class="btn-remove w-100" onclick="removeSection(this)">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    </div>
</template>

<template id="eligibilityTemplate">
    <div class="dynamic-section eligibility-item">
        <div class="d-flex justify-content-between align-items-start mb-2">
            <h6 class="mb-0"><i class="fas fa-award me-2"></i>Certification</h6>
            <button type="button" class="btn-remove" onclick="removeSection(this)">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        <div class="row">
            <div class="col-md-8 mb-2">
                <input type="text" name="eligibility[__INDEX__][title]" class="form-control" placeholder="Certification/License Name" required>
            </div>
            <div class="col-md-4 mb-2">
                <input type="number" name="eligibility[__INDEX__][year]" class="form-control" placeholder="Year Obtained">
            </div>
        </div>
    </div>
</template>

<template id="trainingTemplate">
    <div class="dynamic-section training-item">
        <div class="d-flex justify-content-between align-items-start mb-2">
            <h6 class="mb-0"><i class="fas fa-certificate me-2"></i>Training/Seminar</h6>
            <button type="button" class="btn-remove" onclick="removeSection(this)">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        <div class="row">
            <div class="col-md-6 mb-2">
                <input type="text" name="trainings[__INDEX__][title]" class="form-control" placeholder="Training Title" required>
            </div>
            <div class="col-md-6 mb-2">
                <input type="text" name="trainings[__INDEX__][organizer]" class="form-control" placeholder="Organizer">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-2">
                <input type="date" name="trainings[__INDEX__][date_from]" class="form-control" placeholder="Date From">
            </div>
            <div class="col-md-6 mb-2">
                <input type="date" name="trainings[__INDEX__][date_to]" class="form-control" placeholder="Date To">
            </div>
        </div>
    </div>
</template>
@endsection

@section('scripts')
<script>
let workExperienceIndex = 0;
let educationIndex = 0;
let skillIndex = 0;
let languageIndex = 0;
let eligibilityIndex = 0;
let trainingIndex = 0;

// Photo Preview
document.getElementById('profilePhoto').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('photoPreview').innerHTML = `<img src="${e.target.result}" alt="Profile Photo">`;
        }
        reader.readAsDataURL(file);
    }
});

// Add Work Experience
function addWorkExperience() {
    const template = document.getElementById('workExperienceTemplate');
    const clone = template.content.cloneNode(true);
    const html = clone.firstElementChild.innerHTML.replace(/__INDEX__/g, workExperienceIndex);

    const div = document.createElement('div');
    div.className = 'dynamic-section work-experience-item';
    div.innerHTML = html;

    document.getElementById('workExperienceContainer').appendChild(div);
    workExperienceIndex++;
    updateCompletion();
}

// Add Education
function addEducation() {
    const template = document.getElementById('educationTemplate');
    const clone = template.content.cloneNode(true);
    const html = clone.firstElementChild.innerHTML.replace(/__INDEX__/g, educationIndex);

    const div = document.createElement('div');
    div.className = 'dynamic-section education-item';
    div.innerHTML = html;

    document.getElementById('additionalEducationContainer').appendChild(div);
    educationIndex++;
}

// Add Skill
function addSkill() {
    const template = document.getElementById('skillTemplate');
    const clone = template.content.cloneNode(true);
    const html = clone.firstElementChild.innerHTML.replace(/__INDEX__/g, skillIndex);

    const div = document.createElement('div');
    div.className = 'dynamic-section skill-item';
    div.innerHTML = html;

    document.getElementById('skillsContainer').appendChild(div);
    skillIndex++;
    updateCompletion();
}

// Add Language
function addLanguage() {
    const template = document.getElementById('languageTemplate');
    const clone = template.content.cloneNode(true);
    const html = clone.firstElementChild.innerHTML.replace(/__INDEX__/g, languageIndex);

    const div = document.createElement('div');
    div.className = 'dynamic-section language-item';
    div.innerHTML = html;

    document.getElementById('languagesContainer').appendChild(div);
    languageIndex++;
}

// Add Eligibility
function addEligibility() {
    const template = document.getElementById('eligibilityTemplate');
    const clone = template.content.cloneNode(true);
    const html = clone.firstElementChild.innerHTML.replace(/__INDEX__/g, eligibilityIndex);

    const div = document.createElement('div');
    div.className = 'dynamic-section eligibility-item';
    div.innerHTML = html;

    document.getElementById('eligibilityContainer').appendChild(div);
    eligibilityIndex++;
}

// Add Training
function addTraining() {
    const template = document.getElementById('trainingTemplate');
    const clone = template.content.cloneNode(true);
    const html = clone.firstElementChild.innerHTML.replace(/__INDEX__/g, trainingIndex);

    const div = document.createElement('div');
    div.className = 'dynamic-section training-item';
    div.innerHTML = html;

    document.getElementById('trainingsContainer').appendChild(div);
    trainingIndex++;
}

// Remove Section
function removeSection(button) {
    button.closest('.dynamic-section').remove();
    updateCompletion();
}

// Calculate Completion Percentage
function updateCompletion() {
    let completion = 0;

    // Personal Info (40%)
    if (document.querySelector('[name="surname"]').value) completion += 5;
    if (document.querySelector('[name="first_name"]').value) completion += 5;
    if (document.querySelector('[name="date_of_birth"]').value) completion += 5;
    if (document.querySelector('[name="sex"]').value) completion += 5;
    if (document.querySelector('[name="mobile_number"]').value) completion += 5;
    if (document.querySelector('[name="email_address"]').value) completion += 5;
    if (document.querySelector('[name="province"]').value) completion += 5;
    if (document.querySelector('[name="complete_address"]').value) completion += 5;

    // Professional Summary (10%)
    if (document.querySelector('[name="professional_summary"]').value) completion += 10;

    // Education (15%)
    if (document.querySelector('[name="educational_attainment"]').value) completion += 10;
    if (document.querySelector('[name="course"]').value) completion += 5;

    // Work Experience (15%)
    if (document.querySelectorAll('.work-experience-item').length > 0) completion += 15;

    // Skills (10%)
    if (document.querySelectorAll('.skill-item').length > 0) completion += 10;

    // Photo (10%)
    if (document.getElementById('profilePhoto').files.length > 0) completion += 10;

    // Application Letter (5%)
    if (document.querySelector('[name="application_letter"]').value) completion += 5;

    // Update UI
    document.getElementById('completionProgress').style.width = completion + '%';
    document.querySelector('.completion-badge').textContent = completion + '%';

    if (completion >= 80) {
        document.querySelector('.completion-badge').classList.remove('bg-warning');
        document.querySelector('.completion-badge').classList.add('bg-success');
    } else {
        document.querySelector('.completion-badge').classList.remove('bg-success');
        document.querySelector('.completion-badge').classList.add('bg-warning');
    }
}

// Load existing data
function loadExistingData() {
    // Load work experience
    @if($resume->work_experience && count($resume->work_experience) > 0)
        @foreach($resume->work_experience as $index => $work)
            addWorkExperience();
            const workContainer = document.querySelectorAll('.work-experience-item')[{{ $index }}];
            if (workContainer) {
                workContainer.querySelector('[name*="[company]"]').value = '{{ $work["company"] ?? "" }}';
                workContainer.querySelector('[name*="[position]"]').value = '{{ $work["position"] ?? "" }}';
                workContainer.querySelector('[name*="[start_date]"]').value = '{{ $work["start_date"] ?? "" }}';
                workContainer.querySelector('[name*="[end_date]"]').value = '{{ $work["end_date"] ?? "" }}';
                workContainer.querySelector('[name*="[description]"]').value = '{{ $work["description"] ?? "" }}';
            }
        @endforeach
    @else
        addWorkExperience();
    @endif

    // Load skills
    @if($resume->skills && count($resume->skills) > 0)
        @foreach($resume->skills as $index => $skill)
            addSkill();
            const skillContainer = document.querySelectorAll('.skill-item')[{{ $index }}];
            if (skillContainer) {
                skillContainer.querySelector('[name*="[name]"]').value = '{{ $skill["name"] ?? "" }}';
                skillContainer.querySelector('[name*="[level]"]').value = '{{ $skill["level"] ?? "intermediate" }}';
            }
        @endforeach
    @else
        addSkill();
    @endif

    // Load additional education
    @if($resume->additional_education && count($resume->additional_education) > 0)
        @foreach($resume->additional_education as $index => $edu)
            addEducation();
            const eduContainer = document.querySelectorAll('.education-item')[{{ $index }}];
            if (eduContainer) {
                eduContainer.querySelector('[name*="[school]"]').value = '{{ $edu["school"] ?? "" }}';
                eduContainer.querySelector('[name*="[degree]"]').value = '{{ $edu["degree"] ?? "" }}';
                eduContainer.querySelector('[name*="[year]"]').value = '{{ $edu["year"] ?? "" }}';
            }
        @endforeach
    @endif

    // Load languages
    @if($resume->languages && count($resume->languages) > 0)
        @foreach($resume->languages as $index => $lang)
            addLanguage();
            const langContainer = document.querySelectorAll('.language-item')[{{ $index }}];
            if (langContainer) {
                langContainer.querySelector('[name*="[name]"]').value = '{{ $lang["name"] ?? "" }}';
                langContainer.querySelector('[name*="[proficiency]"]').value = '{{ $lang["proficiency"] ?? "conversational" }}';
            }
        @endforeach
    @endif

    // Load eligibility
    @if($resume->eligibility && count($resume->eligibility) > 0)
        @foreach($resume->eligibility as $index => $cert)
            addEligibility();
            const certContainer = document.querySelectorAll('.eligibility-item')[{{ $index }}];
            if (certContainer) {
                certContainer.querySelector('[name*="[title]"]').value = '{{ $cert["title"] ?? "" }}';
                certContainer.querySelector('[name*="[year]"]').value = '{{ $cert["year"] ?? "" }}';
            }
        @endforeach
    @endif

    // Load trainings
    @if($resume->trainings && count($resume->trainings) > 0)
        @foreach($resume->trainings as $index => $training)
            addTraining();
            const trainingContainer = document.querySelectorAll('.training-item')[{{ $index }}];
            if (trainingContainer) {
                trainingContainer.querySelector('[name*="[title]"]').value = '{{ $training["title"] ?? "" }}';
                trainingContainer.querySelector('[name*="[organizer]"]').value = '{{ $training["organizer"] ?? "" }}';
                trainingContainer.querySelector('[name*="[date_from]"]').value = '{{ $training["date_from"] ?? "" }}';
                trainingContainer.querySelector('[name*="[date_to]"]').value = '{{ $training["date_to"] ?? "" }}';
            }
        @endforeach
    @endif
}

// Form Input Listeners
document.addEventListener('DOMContentLoaded', function() {
    // Load existing data first
    loadExistingData();

    // Listen to all form inputs
    document.getElementById('resumeForm').addEventListener('input', updateCompletion);
    document.getElementById('resumeForm').addEventListener('change', updateCompletion);

    // Initial calculation
    updateCompletion();

    // Form submission validation
    document.getElementById('resumeForm').addEventListener('submit', function(e) {
        const action = e.submitter.value;

        if (action === 'save_and_publish') {
            const completion = parseInt(document.querySelector('.completion-badge').textContent);
            if (completion < 80) {
                e.preventDefault();
                alert('You need to complete at least 80% of your resume to publish it. Current completion: ' + completion + '%');
                return false;
            }
        }
    });
});
</script>
@endsection
