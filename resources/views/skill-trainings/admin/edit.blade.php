@extends('layouts.admin')

@section('title', 'Edit Skill Training - PWD System')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">
                        <i class="fas fa-edit"></i>
                        Edit Skill Training
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.skill-trainings.update', $skillTraining) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="title" class="form-label">Training Title *</label>
                                    <input type="text" class="form-control" id="title" name="title"
                                           value="{{ old('title', $skillTraining->title) }}" required>
                                    @error('title')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="trainer" class="form-label">Trainer *</label>
                                    <input type="text" class="form-control" id="trainer" name="trainer"
                                           value="{{ old('trainer', $skillTraining->trainer) }}" required>
                                    @error('trainer')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="description" class="form-label">Description *</label>
                            <textarea class="form-control" id="description" name="description"
                                      rows="3" required>{{ old('description', $skillTraining->description) }}</textarea>
                            @error('description')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="objectives" class="form-label">Learning Objectives *</label>
                            <textarea class="form-control" id="objectives" name="objectives"
                                      rows="3" required>{{ old('objectives', $skillTraining->objectives) }}</textarea>
                            @error('objectives')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="start_date" class="form-label">Start Date *</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date"
                                           value="{{ old('start_date', $skillTraining->start_date->format('Y-m-d')) }}" required>
                                    @error('start_date')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="end_date" class="form-label">End Date *</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date"
                                           value="{{ old('end_date', $skillTraining->end_date->format('Y-m-d')) }}" required>
                                    @error('end_date')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="location" class="form-label">Location *</label>
                                    <input type="text" class="form-control" id="location" name="location"
                                           value="{{ old('location', $skillTraining->location) }}" required>
                                    @error('location')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="max_participants" class="form-label">Maximum Participants *</label>
                                    <input type="number" class="form-control" id="max_participants" name="max_participants"
                                           value="{{ old('max_participants', $skillTraining->max_participants) }}" min="1" required>
                                    @error('max_participants')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="is_active" name="is_active"
                                       {{ old('is_active', $skillTraining->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Active Training</label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.skill-trainings.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Cancel
                            </a>
                            <div>
                                <a href="{{ route('admin.skill-trainings.show', $skillTraining) }}" class="btn btn-info">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Training
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
