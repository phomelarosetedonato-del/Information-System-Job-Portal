@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create Job Posting</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('job-postings.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="title" class="form-label">Job Title</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="requirements" class="form-label">Requirements</label>
                            <textarea class="form-control" id="requirements" name="requirements" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="location" class="form-label">Location</label>
                            <input type="text" class="form-control" id="location" name="location" required>
                        </div>
                        <div class="mb-3">
                            <label for="company" class="form-label">Company</label>
                            <input type="text" class="form-control" id="company" name="company" required>
                        </div>
                        <div class="mb-3">
                            <label for="salary" class="form-label">Salary</label>
                            <input type="number" step="0.01" class="form-control" id="salary" name="salary">
                        </div>
                        <div class="mb-3">
                            <label for="employment_type" class="form-label">Employment Type</label>
                            <select class="form-control" id="employment_type" name="employment_type" required>
                                <option value="Full-time">Full-time</option>
                                <option value="Part-time">Part-time</option>
                                <option value="Contract">Contract</option>
                                <option value="Temporary">Temporary</option>
                                <option value="Internship">Internship</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="application_deadline" class="form-label">Application Deadline</label>
                            <input type="date" class="form-control" id="application_deadline" name="application_deadline">
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" checked>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                        <button type="submit" class="btn btn-primary">Create Job Posting</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
