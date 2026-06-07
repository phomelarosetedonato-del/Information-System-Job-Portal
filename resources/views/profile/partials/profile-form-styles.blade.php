<style>
    .form-label {
        font-weight: 500;
        color: #495057;
        margin-bottom: 0.5rem;
    }
    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #dee2e6;
        transition: all 0.3s ease;
    }
    .form-control:focus, .form-select:focus {
        border-color: #4361ee;
        box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.25);
    }
    .card {
        border-radius: 12px;
    }
    .card-header {
        border-radius: 12px 12px 0 0 !important;
    }
    .rounded-circle {
        transition: transform 0.3s ease;
    }
    @media (min-width: 768px) {
        .rounded-circle:hover {
            transform: scale(1.05);
        }
    }
    .h3-md { font-size: 1.5rem; }
    .h5-md { font-size: 1.125rem; }
    @media (min-width: 768px) {
        .h3-md { font-size: 1.75rem; }
        .h5-md { font-size: 1.25rem; }
    }
    @media (max-width: 767px) {
        .sticky-bottom-mobile {
            position: sticky;
            bottom: 0;
            z-index: 1020;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 0 !important;
        }
        .sticky-bottom-mobile .card-body {
            padding: 0.75rem !important;
        }
    }
    @media (max-width: 767px) {
        .btn {
            padding: 0.6rem 1rem;
            font-size: 0.9rem;
            min-height: 44px;
        }
        .form-label {
            font-size: 0.9rem;
            font-weight: 600;
        }
        .form-select, .form-control {
            font-size: 0.95rem;
            padding: 0.6rem 0.75rem;
            min-height: 44px;
        }
        .form-control:focus, .form-select:focus {
            font-size: 16px;
        }
    }
    @media (max-width: 576px) {
        .card { margin-bottom: 1rem !important; }
        .card-body { padding: 1rem !important; }
        .card-header { padding: 0.75rem 1rem !important; }
    }
    @media (max-width: 767px) {
        .container { padding-left: 1rem; padding-right: 1rem; }
    }
    .form-select { cursor: pointer; background-position: right 0.75rem center; }
    .form-select:focus { background-color: #fff; }
    #skills_other_container,
    #qualifications_other_container,
    #special_needs_other_container {
        transition: opacity 0.3s ease-in-out;
    }
    @media (max-width: 767px) {
        .form-text { font-size: 0.8rem; }
    }
    @media (min-width: 768px) {
        .row.g-3 > * { margin-bottom: 0.5rem; }
    }
    #submitBtn {
        pointer-events: auto !important;
        cursor: pointer !important;
        opacity: 1 !important;
        position: relative;
        z-index: 10;
    }
    #submitBtn:not(:disabled):hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }
    #submitBtn:not(:disabled):active {
        transform: translateY(0);
    }
</style>
