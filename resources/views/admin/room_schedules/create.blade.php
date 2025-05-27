@extends('admin.master_layout')

@section('title')
    <title>{{ __('Create Room Schedule') }}</title>
@endsection

@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
@endsection

@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Create Room Schedule') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('admin.room_schedules.index') }}">{{ __('Room Schedules') }}</a></div>
                    <div class="breadcrumb-item">{{ __('Create') }}</div>
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
                        <h4>{{ __('New Schedule') }}</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.room_schedules.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>{{ __('Room') }}</label>
                                <select name="room_id" class="form-control">
                                    <option value="">{{ __('Select Room') }}</option>
                                    @foreach ($rooms as $room)
                                        <option value="{{ $room->room_id }}" {{ old('room_id') == $room->room_id ? 'selected' : '' }}>{{ $room->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>{{ __('Day of Week') }}</label>
                                <select name="day_of_week" class="form-control">
                                    <option value="">{{ __('Select Day') }}</option>
                                    @foreach ($days as $day)
                                        <option value="{{ $day }}" {{ old('day_of_week') == $day ? 'selected' : '' }}>{{ $day }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>{{ __('Start Time') }}</label>
                                <input type="time" name="start_time" class="form-control" value="{{ old('start_time') }}">
                            </div>
                            <div class="form-group">
                                <label>{{ __('End Time') }}</label>
                                <input type="time" name="end_time" class="form-control" value="{{ old('end_time') }}">
                            </div>
                            <div class="form-group">
                                <label>{{ __('Available') }}</label>
                                <select name="is_available" class="form-control">
                                    <option value="1" {{ old('is_available') == '1' ? 'selected' : '' }}>{{ __('Available') }}</option>
                                    <option value="0" {{ old('is_available') == '0' ? 'selected' : '' }}>{{ __('Unavailable') }}</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
@endsection
