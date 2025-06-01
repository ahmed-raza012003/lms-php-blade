@extends('admin.master_layout')

@section('title')
    <title>{{ __('Class Enrollment Details') }}</title>
@endsection

@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <style>
        .error { color: #dc3545; font-size: 13px; margin-top: 5px; }
    </style>
@endsection

@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Class Enrollment Details') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('admin.class-enrollments.index') }}">{{ __('Class Enrollments') }}</a></div>
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
                        <h4>{{ __('Enrollment for ') }}{{ $classEnrollment->class->subject ?? 'Unknown Class' }}</h4>
                        <div class="card-header-action">
                            <a href="{{ route('admin.class-enrollments.edit', $classEnrollment->enrollment_id) }}" class="btn btn-primary">{{ __('Edit') }}</a>
                            <form action="{{ route('admin.class-enrollments.destroy', $classEnrollment->enrollment_id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('{{ __('Are you sure you want to delete this enrollment?') }}')">{{ __('Delete') }}</button>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>{{ __('Class') }}:</strong> {{ $classEnrollment->class->subject ?? 'Unknown Class' }}</p>
                                <p><strong>{{ __('Student') }}:</strong> {{ $classEnrollment->student->name ?? 'Unknown Student' }}</p>
                                <p><strong>{{ __('Enrollment Date') }}:</strong> {{ $classEnrollment->enrollment_date->format('d M Y H:i') }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>{{ __('Status') }}:</strong> {{ ucfirst($classEnrollment->status) }}</p>
                                <p><strong>{{ __('Created At') }}:</strong> {{ $classEnrollment->created_at->format('d M Y H:i') }}</p>
                                <p><strong>{{ __('Updated At') }}:</strong> {{ $classEnrollment->updated_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('admin.class-enrollments.index') }}" class="btn btn-secondary">{{ __('Back to List') }}</a>
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
