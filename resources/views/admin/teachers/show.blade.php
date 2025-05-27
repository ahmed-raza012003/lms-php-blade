@extends('admin.master_layout')

@section('title')
    <title>{{ __('Teacher Details') }}</title>
@endsection

@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <style>
        .detail-label { font-weight: 500; color: #343a40; width: 200px; }
        .detail-value { color: #495057; }
        .credential-img { max-width: 60px; height: auto; border-radius: 4px; }
    </style>
@endsection

@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Teacher Details') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('admin.teachers.index') }}">{{ __('Teachers') }}</a></div>
                    <div class="breadcrumb-item active">{{ __('Details') }}</div>
                </div>
            </div>

            <div class="section-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        {{ session('error') }}
                    </div>
                @endif

                <div class="card">
                    <div class="card-header">
                        <h4>{{ __('Teacher Information') }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="d-flex mb-3">
                                    <div class="detail-label">{{ __('Name') }}:</div>
                                    <div class="detail-value">{{ $teacher->first_name }} {{ $teacher->last_name }}</div>
                                </div>
                                <div class="d-flex mb-3">
                                    <div class="detail-label">{{ __('Email') }}:</div>
                                    <div class="detail-value">{{ $teacher->user->email ?? 'N/A' }}</div>
                                </div>
                                <div class="d-flex mb-3">
                                    <div class="detail-label">{{ __('Bio') }}:</div>
                                    <div class="detail-value">{{ $teacher->bio ?? 'N/A' }}</div>
                                </div>
                                <div class="d-flex mb-3">
                                    <div class="detail-label">{{ __('Subjects') }}:</div>
                                    <div class="detail-value">{{ $teacher->subjects ? implode(', ', $teacher->subjects) : 'N/A' }}</div>
                                </div>
                                <div class="d-flex mb-3">
                                    <div class="detail-label">{{ __('School Levels') }}:</div>
                                    <div class="detail-value">{{ $teacher->school_levels ? implode(', ', $teacher->school_levels) : 'N/A' }}</div>
                                </div>
                                <div class="d-flex mb-3">
                                    <div class="detail-label">{{ __('Hourly Rate') }}:</div>
                                    <div class="detail-value">{{ $teacher->hourly_rate ? '$' . number_format($teacher->hourly_rate, 2) : 'N/A' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex mb-3">
                                    <div class="detail-label">{{ __('Availability') }}:</div>
                                    <div class="detail-value">{{ $teacher->availability ? implode(', ', $teacher->availability) : 'N/A' }}</div>
                                </div>
                                <div class="d-flex mb-3">
                                    <div class="detail-label">{{ __('Rating') }}:</div>
                                    <div class="detail-value">{{ $teacher->rating ? number_format($teacher->rating, 1) . '/5' : 'N/A' }}</div>
                                </div>
                                <div class="d-flex mb-3">
                                    <div class="detail-label">{{ __('Payment Info') }}:</div>
                                    <div class="detail-value">{{ $teacher->payment_info ?? 'N/A' }}</div>
                                </div>
                                <div class="d-flex mb-3">
                                    <div class="detail-label">{{ __('Highest Qualification') }}:</div>
                                    <div class="detail-value">{{ $teacher->highest_qualification ?? 'N/A' }}</div>
                                </div>
                                <div class="d-flex mb-3">
                                    <div class="detail-label">{{ __('Years of Experience') }}:</div>
                                    <div class="detail-value">{{ $teacher->years_of_experience ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </div>

                        <h5 class="mt-4">{{ __('Degrees') }}</h5>
                        @if ($teacher->degrees->isNotEmpty())
                            <ul class="list-unstyled">
                                @foreach ($teacher->degrees as $degree)
                                    <li class="mb-2">
                                        {{ $degree->name }}
                                        @if ($degree->file_path)
                                            @if (pathinfo($degree->file_path, PATHINFO_EXTENSION) == 'pdf')
                                                <a href="{{ Storage::url($degree->file_path) }}" target="_blank" class="ml-2 text-primary"><i class="fas fa-file-pdf"></i> {{ __('View PDF') }}</a>
                                            @else
                                                <img src="{{ Storage::url($degree->file_path) }}" alt="Degree" class="credential-img ml-2">
                                            @endif
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p>{{ __('No Degrees') }}</p>
                        @endif

                        <h5 class="mt-4">{{ __('Certifications') }}</h5>
                        @if ($teacher->certifications->isNotEmpty())
                            <ul class="list-unstyled">
                                @foreach ($teacher->certifications as $cert)
                                    <li class="mb-2">
                                        {{ $cert->name }}
                                        @if ($cert->file_path)
                                            @if (pathinfo($cert->file_path, PATHINFO_EXTENSION) == 'pdf')
                                                <a href="{{ Storage::url($cert->file_path) }}" target="_blank" class="ml-2 text-primary"><i class="fas fa-file-pdf"></i> {{ __('View PDF') }}</a>
                                            @else
                                                <img src="{{ Storage::url($cert->file_path) }}" alt="Certification" class="credential-img ml-2">
                                            @endif
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p>{{ __('No Certifications') }}</p>
                        @endif
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('admin.teachers.index') }}" class="btn btn-secondary">{{ __('Back') }}</a>
                        <a href="{{ route('admin.teachers.edit', $teacher->teacher_id) }}" class="btn btn-primary">{{ __('Edit') }}</a>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        $(document).ready(function() {
            toastr.options = { closeButton: true, progressBar: true, positionClass: 'toast-top-right', timeOut: 5000 };
        });
    </script>
@endsection
