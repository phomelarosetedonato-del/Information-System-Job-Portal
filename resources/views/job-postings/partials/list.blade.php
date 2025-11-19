@if(isset($jobPostings) && $jobPostings->count() > 0)
    <div class="row">
        @foreach($jobPostings as $job)
            <div class="col-md-6 mb-4">
                <div class="card h-100 job-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ $job->title }}</h5>
                        @if(isset($job->match_score))
                            <small class="text-muted ms-2">Match: <strong>{{ $job->match_score }}</strong></small>
                        @endif
                        @if(auth()->user() && auth()->user()->role === 'pwd')
                            @php
                                $hasApplied = auth()->user()->jobApplications()
                                    ->where('job_posting_id', $job->id)
                                    ->exists();
                            @endphp
                            @if($hasApplied)
                                <span class="badge bg-success">Applied</span>
                            @else
                                @if(auth()->user()->can_apply_for_jobs)
                                    <form action="{{ route('job.apply', $job) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm" style="background: linear-gradient(90deg, #1A5D34 0%, #2E8B57 100%); color: white; border: none;">
                                            <i class="fas fa-paper-plane"></i> Apply Now
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-outline-warning btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#resumeRequiredModal"
                                            title="Upload resume to apply">
                                        <i class="fas fa-file-upload"></i> Upload Resume
                                    </button>
                                @endif
                            @endif
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <strong><i class="fas fa-building"></i> Company:</strong>
                            {{ $job->company }}
                        </div>
                        <div class="mb-2">
                            <strong><i class="fas fa-map-marker-alt"></i> Location:</strong>
                            {{ $job->location }}
                        </div>
                        <div class="mb-2">
                            <strong><i class="fas fa-clock"></i> Employment Type:</strong>
                            {{ $job->employment_type }}
                        </div>
                        @if($job->salary)
                            <div class="mb-2">
                                <strong><i class="fas fa-money-bill-wave"></i> Salary:</strong>
                                {{ $job->salary }}
                            </div>
                        @endif
                        @if($job->application_deadline)
                            <div class="mb-3">
                                <strong><i class="fas fa-calendar-times"></i> Application Deadline:</strong>
                                <span class="{{ $job->application_deadline->isPast() ? 'text-danger' : 'text-success' }}">
                                    {{ $job->formatted_deadline }}
                                </span>
                            </div>
                        @else
                            <div class="mb-3">
                                <strong><i class="fas fa-calendar-times"></i> Application Deadline:</strong>
                                <span class="text-info">No deadline</span>
                            </div>
                        @endif
                        <div class="job-description">
                            <strong>Description:</strong>
                            <p class="mt-1">{{ Str::limit($job->description, 150) }}</p>
                        </div>
                        {{-- Accessibility badges --}}
                        <div class="mt-2">
                            @if(isset($job->provides_accommodations) && $job->provides_accommodations)
                                <span class="badge bg-info me-1" title="Provides workplace accommodations">Accessibility: Yes</span>
                            @endif
                            @if(isset($job->is_remote) && $job->is_remote)
                                <span class="badge bg-secondary me-1" title="Remote friendly">Remote</span>
                            @endif
                            @if(method_exists($job, 'suitableDisabilityTypes') && $job->suitableDisabilityTypes->count())
                                <small class="text-muted d-block mt-1">Suitable for: {{ $job->suitableDisabilityTypes->pluck('type')->take(3)->implode(', ') }}</small>
                            @endif
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('job-postings.public.show', $job) }}" class="btn btn-sm" style="border: 1px solid #2E8B57; color: #2E8B57;">
                            <i class="fas fa-eye"></i> View Details
                        </a>
                        @if(auth()->user() && auth()->user()->role === 'pwd' && empty($hasApplied))
                            @if(auth()->user()->can_apply_for_jobs)
                                <form action="{{ route('job.apply', $job) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm" style="background: linear-gradient(90deg, #1A5D34 0%, #2E8B57 100%); color: white; border: none;">
                                        <i class="fas fa-paper-plane"></i> Quick Apply
                                    </button>
                                </form>
                            @else
                                <button class="btn btn-outline-warning btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#resumeRequiredModal">
                                    <i class="fas fa-file-upload"></i> Upload to Apply
                                </button>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

@else
    <div class="text-center py-5">
        <i class="fas fa-briefcase fa-4x text-muted mb-3"></i>
        <h3 class="text-muted">No Job Opportunities Available</h3>
        <p class="text-muted">There are currently no active job postings. Please check back later.</p>
    </div>
@endif

@auth
    @if(auth()->user()->role === 'pwd')
        @include('partials.resume-required-modal')
    @endif
@endauth
