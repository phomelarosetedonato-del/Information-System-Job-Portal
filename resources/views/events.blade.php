@extends('layouts.app')

@section('title', 'Events - PWD System')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-calendar-alt me-2"></i>
                        Upcoming Events
                    </h4>
                </div>

                <div class="card-body">
                    <div class="text-center py-5">
                        <i class="fas fa-calendar-plus fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Events Coming Soon</h5>
                        <p class="text-muted">We're preparing some exciting events and activities for our PWD community.</p>
                        <p class="text-muted">Check back later for updates on workshops, job fairs, and training sessions.</p>
                        <a href="{{ route('dashboard') }}" class="btn btn-primary mt-3">
                            <i class="fas fa-home me-1"></i>Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
