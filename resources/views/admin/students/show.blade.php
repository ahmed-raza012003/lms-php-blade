@extends('admin.master_layout')

@section('title')
    <title>{{ __('View Student') }}</title>
@endsection

@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <style>
        .error {
            color: #dc3545;
            font-size: 13px;
            margin-top: 5px;
        }
        .detail-label {
            font-weight: bold;
        }
    </style>
@endsection

@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('View Student') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('admin.students.index') }}">{{ __('Students') }}</a></div>
                    <div class="breadcrumb-item">{{ __('View') }}</div>
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
                        <h4>{{ __('Student Details') }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="detail-label">{{ __('User') }}</label>
                            <p>{{ $student->user ? $student->user->name : 'N/A' }}</p>
                        </div>
                        <div class="form-group">
                            <label class="detail-label">{{ __('Grade Level') }}</label>
                            <p>{{ $student->grade_level ?? 'N/A' }}</p>
                        </div>
                        <div class="form-group">
                            <label class="detail-label">{{ __('Parent') }}</label>
                            <p>{{ $student->parent && $student->parent->user ? $student->parent->user->name : 'N/A' }}</p>
                        </div>
                        <div class="form-group">
                            <label class="detail-label">{{ __('Bio') }}</label>
                            <p>{{ $student->bio ?? 'N/A' }}</p>
                        </div>
                        <div class="form-group">
                            <label class="detail-label">{{ __('Profile Photo') }}</label>
                            <p>
                                @if ($student->profile_photo)
                                    <a href="{{ Storage::url($student->profile_photo) }}" target="_blank">View Photo</a>
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>
                        <div class="form-group">
                            <label class="detail-label">{{ __('Payment Info') }}</label>
                            <p>{{ $student->payment_info ?? 'N/A' }}</p>
                        </div>
                        <div class="form-group">
                            <label class="detail-label">{{ __('Status') }}</label>
                            <p>{{ $student->status ? ucfirst($student->status) : 'N/A' }}</p>
                        </div>
                        <div class="form-group">
                            <a href="{{ route('admin.students.edit', $student->student_id) }}" class="btn btn-primary">{{ __('Edit') }}</a>
                            <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">{{ __('Back') }}</a>
                        </div>
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
            toastr.options = {
                closeButton: true,
                progressBar: true,
                positionClass: 'toast-top-right',
                timeOut: 5000
            };
            @if (session('success'))
                toastr.success('{{ session('success') }}');
            @endif
            @if (session('error'))
                toastr.error('{{ session('error') }}');
            @endif
        });
    </script>
@endsection