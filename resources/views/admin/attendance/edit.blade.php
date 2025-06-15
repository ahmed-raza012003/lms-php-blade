@extends('admin.master_layout')

@section('title')
    <title>{{ __('Edit Attendance') }}</title>
@endsection

@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <style>
        .error {
            color: #dc3545;
            font-size: 13px;
            margin-top: 5px;
        }
    </style>
@endsection

@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Edit Attendance') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('admin.attendance.index') }}">{{ __('Attendance') }}</a></div>
                    <div class="breadcrumb-item">{{ __('Edit') }}</div>
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
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card">
                    <div class="card-header">
                        <h4>{{ __('Edit Attendance') }}</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.attendance.update', $attendance->attendance_id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label>{{ __('Class') }}</label>
                                <select name="class_id" class="form-control" required>
                                    <option value="">{{ __('Select Class') }}</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->class_id }}"
                                            {{ old('class_id', $attendance->class_id) == $class->class_id ? 'selected' : '' }}>
                                            {{ $class->subject }}</option>
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
                                        <option value="{{ $student->student_id }}"
                                            {{ old('student_id', $attendance->student_id) == $student->student_id ? 'selected' : '' }}>
                                            {{ $student->user->name ?? 'N/A' }}</option>
                                    @endforeach
                                </select>
                                @error('student_id')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>{{ __('Date') }}</label>
                                <input type="date" name="date" class="form-control" value="{{ old('date', $attendance->date->format('Y-m-d')) }}"
                                    required max="{{ date('Y-m-d') }}">
                                @error('date')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>{{ __('Status') }}</label>
                                <select name="status" class="form-control" required>
                                    <option value="">{{ __('Select Status') }}</option>
                                    <option value="present" {{ old('status', $attendance->status) == 'present' ? 'selected' : '' }}>
                                        {{ __('Present') }}</option>
                                    <option value="absent" {{ old('status', $attendance->status) == 'absent' ? 'selected' : '' }}>
                                        {{ __('Absent') }}</option>
                                    <option value="late" {{ old('status', $attendance->status) == 'late' ? 'selected' : '' }}>
                                        {{ __('Late') }}</option>
                                </select>
                                @error('status')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>{{ __('Marked By') }}</label>
                                <select name="marked_by" class="form-control" required>
                                    <option value="">{{ __('Select Teacher') }}</option>
                                    @foreach ($teachers as $teacher)
                                        <option value="{{ $teacher->teacher_id }}"
                                            {{ old('marked_by', $attendance->marked_by) == $teacher->teacher_id ? 'selected' : '' }}>
                                            {{ $teacher->user->name ?? 'N/A' }}</option>
                                    @endforeach
                                </select>
                                @error('marked_by')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                                <a href="{{ route('admin.attendance.index') }}"
                                    class="btn btn-secondary">{{ __('Cancel') }}</a>
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