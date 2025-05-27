@extends('admin.master_layout')

@section('title')
    <title>{{ __('Room Details') }}</title>
@endsection

@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
@endsection

@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Room Details') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('admin.rooms.index') }}">{{ __('Rooms') }}</a></div>
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
                        <h4>{{ $room->name }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>{{ __('Workspace') }}:</strong> {{ $room->workspace->name ?? 'N/A' }}</p>
                                <p><strong>{{ __('Description') }}:</strong> {{ $room->description ?? 'N/A' }}</p>
                                <p><strong>{{ __('Size') }}:</strong> {{ $room->size ?? 'N/A' }}</p>
                                <p><strong>{{ __('Price Per Hour') }}:</strong> {{ number_format($room->price_per_hour, 2) }}</p>
                                <p><strong>{{ __('Capacity') }}:</strong> {{ $room->capacity }}</p>
                                <p><strong>{{ __('Status') }}:</strong>
                                    <span class="badge {{ $room->status == 'active' ? 'badge-success' : ($room->status == 'inactive' ? 'badge-danger' : 'badge-secondary') }}">
                                        {{ $room->status ? ucfirst($room->status) : 'None' }}
                                    </span>
                                </p>
                            </div>
                            <div class="col-md-6">
                                @if ($room->profile_photo)
                                    <p><strong>{{ __('Profile Photo') }}:</strong></p>
                                    <img src="{{ asset('storage/' . $room->profile_photo) }}" alt="Profile Photo" class="img-fluid" style="max-width: 300px;">
                                @endif
                                @if ($room->profile_video)
                                    <p><strong>{{ __('Profile Video') }}:</strong></p>
                                    <video src="{{ asset('storage/' . $room->profile_video) }}" controls class="img-fluid" style="max-width: 300px;"></video>
                                @endif
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('admin.rooms.edit', $room->room_id) }}" class="btn btn-primary">{{ __('Edit') }}</a>
                            <form action="{{ route('admin.rooms.destroy', $room->room_id) }}" method="POST" class="d-inline" onclick="return confirm('Are you sure you want to delete this room?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">{{ __('Delete') }}</button>
                            </form>
                            <a href="{{ route('admin.rooms.index') }}" class="btn btn-secondary">{{ __('Back to List') }}</a>
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
