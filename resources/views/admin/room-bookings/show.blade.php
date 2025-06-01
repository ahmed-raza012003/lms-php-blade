@extends('admin.master_layout')

@section('title')
    <title>{{ __('Room Booking Details') }}</title>
@endsection

@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
@endsection

@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Room Booking Details') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('admin.room-bookings.index') }}">{{ __('Room Bookings') }}</a></div>
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
                        <h4>{{ __('Booking Information') }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>{{ __('Booking ID') }}:</strong> {{ $roomBooking->booking_id }}</p>
                                <p><strong>{{ __('Room') }}:</strong> {{ $roomBooking->room->name }}</p>
                                <p><strong>{{ __('Teacher') }}:</strong> {{ $roomBooking->teacher->first_name }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>{{ __('Start Time') }}:</strong> {{ $roomBooking->start_time->format('Y-m-d H:i') }}</p>
                                <p><strong>{{ __('End Time') }}:</strong> {{ $roomBooking->end_time->format('Y-m-d H:i') }}</p>
                                <p><strong>{{ __('Status') }}:</strong> {{ ucfirst($roomBooking->status) }}</p>
                            </div>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('admin.room-bookings.edit', $roomBooking->booking_id) }}" class="btn btn-warning">{{ __('Edit') }}</a>
                            <a href="{{ route('admin.room-bookings.index') }}" class="btn btn-secondary">{{ __('Back') }}</a>
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