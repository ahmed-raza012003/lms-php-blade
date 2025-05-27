@extends('admin.master_layout')

@section('title')
    <title>{{ __('Class Details') }}</title>
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
                <h1>{{ __('Class Details') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('admin.classes.index') }}">{{ __('Classes') }}</a></div>
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
                        <h4>{{ __('Class: ') }}{{ $class->subject }}</h4>
                        <div class="card-header-action">
                            <a href="{{ route('admin.classes.edit', $class->class_id) }}" class="btn btn-primary">{{ __('Edit') }}</a>
                            <form action="{{ route('admin.classes.destroy', $class->class_id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('{{ __('Are you sure you want to delete this class?') }}')">{{ __('Delete') }}</button>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>{{ __('Workspace') }}:</strong> {{ $class->workspace->name ?? 'Unknown Workspace' }}</p>
                                <p><strong>{{ __('Teacher') }}:</strong> {{ $class->teacher ? ($class->teacher->name ?? $class->teacher->user->name ?? 'Unknown Teacher') : 'Unknown Teacher' }}</p>
                                <p><strong>{{ __('Room') }}:</strong> {{ $class->room->name ?? 'Unknown Room' }}</p>
                                <p><strong>{{ __('Subject') }}:</strong> {{ $class->subject }}</p>
                                <p><strong>{{ __('School Level') }}:</strong> {{ $class->school_level }}</p>
                                <p><strong>{{ __('Price') }}:</strong> {{ number_format($class->price, 2) }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>{{ __('Start Time') }}:</strong> {{ $class->start_time->format('d M Y H:i') }}</p>
                                <p><strong>{{ __('End Time') }}:</strong> {{ $class->end_time->format('d M Y H:i') }}</p>
                                <p><strong>{{ __('Recurring') }}:</strong> {{ $class->is_recurring ? __('Yes') : __('No') }}</p>
                                <p><strong>{{ __('Recurrence Pattern') }}:</strong> {{ $class->recurrence_pattern ?? __('N/A') }}</p>
                                <p><strong>{{ __('Status') }}:</strong> {{ ucfirst($class->status) }}</p>
                                <p><strong>{{ __('Created At') }}:</strong> {{ $class->created_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('admin.classes.index') }}" class="btn btn-secondary">{{ __('Back to List') }}</a>
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