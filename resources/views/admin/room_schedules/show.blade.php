@extends('admin.master_layout')

@section('title')
    <title>{{ __('Room Schedule Details') }}</title>
@endsection

@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
@endsection

@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Room Schedule Details') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('admin.room_schedules.index') }}">{{ __('Room Schedules') }}</a></div>
                    <div class="breadcrumb-item">{{ __('Details') }}</div>
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
                        <h4>{{ __('Schedule for ') }}{{ $schedule->room->name ?? 'N/A' }}</h4>
                    </div>
                    <div class="card-body">
                        <p><strong>{{ __('Room') }}:</strong> {{ $schedule->room->name ?? 'N/A' }}</p>
                        <p><strong>{{ __('Day of Week') }}:</strong> {{ $schedule->day_of_week }}</p>
                        <p><strong>{{ __('Start Time') }}:</strong> {{ $schedule->start_time }}</p>
                        <p><strong>{{ __('End Time') }}:</strong> {{ $schedule->end_time }}</p>
                        <p><strong>{{ __('Available') }}:</strong>
                            <span class="badge {{ $schedule->is_available ? 'badge-success' : 'badge-danger' }}">
                                {{ $schedule->is_available ? __('Available') : __('Unavailable') }}
                            </span>
                        </p>
                        <div class="mt-4">
                            <a href="{{ route('admin.room_schedules.edit', $schedule->schedule_id) }}" class="btn btn-primary">{{ __('Edit') }}</a>
                            <form action="{{ route('admin.room_schedules.destroy', $schedule->schedule_id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('Are you sure you want to delete this schedule?') }}');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">{{ __('Delete') }}</button>
                            </form>
                            <a href="{{ route('admin.room_schedules.index') }}" class="btn btn-secondary">{{ __('Back to List') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
@endsection
