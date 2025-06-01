@extends('admin.master_layout')

@section('title')
    <title>{{ __('Edit Class Enrollment') }}</title>
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
                <h1>{{ __('Edit Class Enrollment') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('admin.class-enrollments.index') }}">{{ __('Class Enrollments') }}</a></div>
                    <div class="breadcrumb-item active">{{ __('Edit') }}</div>
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
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card">
                    <div class="card-header">
                        <h4>{{ __('Edit Enrollment') }}</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.class-enrollments.update', $classEnrollment->enrollment_id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label>{{ __('Class') }}</label>
                                <select name="class_id" class="form-control" required>
                                    <option value="">{{ __('Select Class') }}</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->class_id }}" {{ old('class_id', $classEnrollment->class_id) == $class->class_id ? 'selected' : '' }}>{{ $class->subject }}</option>
                                    @endforeach
                                </select>
                                @error('class_id')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>{{ __('Student') }}</label>
                                <select name="student_id" class="form-control" required>
                                    <option value="">{{ __('Select Student') }}</option>
                                    @foreach ($students as $student)
                                        <option value="{{ $student->id }}" {{ old('student_id', $classEnrollment->student_id) == $student->id ? 'selected' : '' }}>{{ $student->name }}</option>
                                    @endforeach
                                </select>
                                @error('student_id')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>{{ __('Enrollment Date') }}</label>
                                <input type="datetime-local" name="enrollment_date" class="form-control" value="{{ old('enrollment_date', $classEnrollment->enrollment_date->format('Y-m-d\TH:i')) }}" required>
                                @error('enrollment_date')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>{{ __('Status') }}</label>
                                <select name="status" class="form-control" required>
                                    <option value="">{{ __('Select Status') }}</option>
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status }}" {{ old('status', $classEnrollment->status) == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                                <a href="{{ route('admin.class-enrollments.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                            </div>
                        </form>
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

            @if (session('success'))
                toastr.success('{{ session('success') }}');
            @endif
            @if (session('error'))
                toastr.error('{{ session('error') }}');
            @endif
        });
    </script>
@endsection
