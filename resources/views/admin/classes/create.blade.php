@extends('admin.master_layout')

@section('title')
    <title>{{ __('Create Class') }}</title>
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
                <h1>{{ __('Create Class') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('admin.classes.index') }}">{{ __('Classes') }}</a></div>
                    <div class="breadcrumb-item active">{{ __('Create') }}</div>
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
                        <h4>{{ __('New Class') }}</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.classes.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>{{ __('Workspace') }}</label>
                                <select name="workspace_id" id="workspace_id" class="form-control" required>
                                    <option value="">{{ __('Select Workspace') }}</option>
                                    @foreach ($workspaces as $workspace)
                                        <option value="{{ $workspace->workspace_id }}" {{ old('workspace_id') == $workspace->workspace_id ? 'selected' : '' }}>{{ $workspace->name }}</option>
                                    @endforeach
                                </select>
                                @error('workspace_id')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>{{ __('Teacher') }}</label>
                                <select name="teacher_id" id="teacher_id" class="form-control" required>
                                    <option value="">{{ __('Select Teacher') }}</option>
                                    @foreach ($teachers as $teacher)
                                        <option value="{{ $teacher->teacher_id }}" {{ old('teacher_id') == $teacher->teacher_id ? 'selected' : '' }}>{{ $teacher->name ?? $teacher->user->name }}</option>
                                    @endforeach
                                </select>
                                @error('teacher_id')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>{{ __('Room') }}</label>
                                <select name="room_id" class="form-control" required>
                                    <option value="">{{ __('Select Room') }}</option>
                                    @foreach ($rooms as $room)
                                        <option value="{{ $room->room_id }}" {{ old('room_id') == $room->room_id ? 'selected' : '' }}>{{ $room->name }}</option>
                                    @endforeach
                                </select>
                                @error('room_id')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>{{ __('Subject') }}</label>
                                <input type="text" name="subject" class="form-control" value="{{ old('subject') }}" required>
                                @error('subject')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>{{ __('School Level') }}</label>
                                <select name="school_level" class="form-control" required>
                                    <option value="">{{ __('Select Level') }}</option>
                                    @foreach ($school_levels as $level)
                                        <option value="{{ $level }}" {{ old('school_level') == $level ? 'selected' : '' }}>{{ $level }}</option>
                                    @endforeach
                                </select>
                                @error('school_level')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>{{ __('Price') }}</label>
                                <input type="number" name="price" class="form-control" value="{{ old('price') }}" step="0.01" required>
                                @error('price')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>{{ __('Start Time') }}</label>
                                <input type="datetime-local" name="start_time" class="form-control" value="{{ old('start_time') }}" required>
                                @error('start_time')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>{{ __('End Time') }}</label>
                                <input type="datetime-local" name="end_time" class="form-control" value="{{ old('end_time') }}" required>
                                @error('end_time')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>{{ __('Recurring') }}</label>
                                <select name="is_recurring" class="form-control" required>
                                    <option value="0" {{ old('is_recurring') == '0' ? 'selected' : '' }}>{{ __('No') }}</option>
                                    <option value="1" {{ old('is_recurring') == '1' ? 'selected' : '' }}>{{ __('Yes') }}</option>
                                </select>
                                @error('is_recurring')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>{{ __('Recurrence Pattern') }}</label>
                                <select name="recurrence_pattern" class="form-control">
                                    <option value="">{{ __('Select Pattern') }}</option>
                                    @foreach ($recurrence_patterns as $pattern)
                                        <option value="{{ $pattern }}" {{ old('recurrence_pattern') == $pattern ? 'selected' : '' }}>{{ $pattern }}</option>
                                    @endforeach
                                </select>
                                @error('recurrence_pattern')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>{{ __('Status') }}</label>
                                <select name="status" class="form-control" required>
                                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                                    <option value="approved" {{ old('status') == 'approved' ? 'selected' : '' }}>{{ __('Approved') }}</option>
                                    <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>{{ __('Cancelled') }}</option>
                                </select>
                                @error('status')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                                <a href="{{ route('admin.classes.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
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