@extends('employer.layouts.employer')

@section('title', 'Create Job Posting')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <h2 class="fw-bold">
            <i class="fas fa-plus-circle text-primary"></i> Create New Job Posting
        </h2>
    </div>
    <div class="col-12 col-md-12 col-lg-10 mx-auto">
        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST" action="{{ route('employer.job-postings.store') }}">
                    @csrf
                    <div class="row">
                        <div class="col-12 col-md-6 mb-3">
                            <label for="title" class="form-label">Job Title *</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <label for="company" class="form-label">Company *</label>
                            <input type="text" class="form-control @error('company') is-invalid @enderror" id="company" name="company" value="{{ old('company', Auth::user()->company_name) }}" required>
                            @error('company')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <label for="location_id" class="form-label">Location *</label>
                            <select class="form-select @error('location_id') is-invalid @enderror" id="location_id" name="location_id" required>
                                <option value="">Select Location</option>
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>{{ $location->name }}</option>
                                @endforeach
                            </select>
                            @error('location_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <label for="employment_type" class="form-label">Employment Type *</label>
                            <select class="form-select @error('employment_type') is-invalid @enderror" id="employment_type" name="employment_type" required>
                                <option value="">Select Type</option>
                                @foreach($employmentTypes as $type)
                                    <option value="{{ $type }}" {{ old('employment_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                                @endforeach
                            </select>
                            @error('employment_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <label for="salary" class="form-label">Salary</label>
                            <input type="number" class="form-control @error('salary') is-invalid @enderror" id="salary" name="salary" value="{{ old('salary') }}" min="0">
                            @error('salary')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="application_deadline" class="form-label">Application Deadline</label>
                            <input type="date" class="form-control @error('application_deadline') is-invalid @enderror" id="application_deadline" name="application_deadline" value="{{ old('application_deadline') }}">
                            @error('application_deadline')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Qualified Disability Types</label>
                            <div class="row">
                                @foreach($disabilityTypes as $type)
                                    <div class="col-12 col-md-4 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="disability_type_ids[]" value="{{ $type->id }}" id="disability_type_{{ $type->id }}"
                                                {{ is_array(old('disability_type_ids')) && in_array($type->id, old('disability_type_ids', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="disability_type_{{ $type->id }}">
                                                {{ $type->name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="requirements" class="form-label">Job Requirements *</label>
                            <textarea class="form-control @error('requirements') is-invalid @enderror" id="requirements" name="requirements" rows="4" required>{{ old('requirements') }}</textarea>
                            @error('requirements')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 mb-3">
                            <label for="description" class="form-label">Job Description *</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5" required>{{ old('description') }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Create Job Posting</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
