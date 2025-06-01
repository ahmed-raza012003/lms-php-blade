@extends('admin.master_layout')

@section('title')
    <title>{{ __('Room Bookings') }}</title>
@endsection

@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
@endsection

@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Room Bookings') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></div>
                    <div class="breadcrumb-item">{{ __('Room Bookings') }}</div>
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
                        <h4>{{ __('All Bookings') }}</h4>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('admin.room-bookings.create') }}" class="btn btn-primary mb-3">{{ __('Create New Booking') }}</a>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>{{ __('Booking ID') }}</th>
                                    <th>{{ __('Room') }}</th>
                                    <th>{{ __('Teacher') }}</th>
                                    <th>{{ __('Start Time') }}</th>
                                    <th>{{ __('End Time') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($bookings as $booking)
                                    <tr>
                                        <td>{{ $booking->booking_id }}</td>
                                        <td>{{ $booking->room->name }}</td>
                                        <td>{{ $booking->teacher->first_name }}</td>
                                        <td>{{ $booking->start_time->format('Y-m-d H:i') }}</td>
                                        <td>{{ $booking->end_time->format('Y-m-d H:i') }}</td>
                                        <td>{{ ucfirst($booking->status) }}</td>
                                        <td>
                                            <a href="{{ route('admin.room-bookings.show', $booking->booking_id) }}" class="btn btn-info btn-sm">{{ __('View') }}</a>
                                            <a href="{{ route('admin.room-bookings.edit', $booking->booking_id) }}" class="btn btn-warning btn-sm">{{ __('Edit') }}</a>
                                            <form action="{{ route('admin.room-bookings.destroy', $booking->booking_id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('{{ __('Are you sure?') }}')">{{ __('Delete') }}</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">{{ __('No bookings found') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        {{ $bookings->links() }}
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
@endsection