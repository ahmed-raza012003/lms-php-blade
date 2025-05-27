@extends('admin.master_layout')

@section('title')
    <title>{{ __('Edit Room') }}</title>
@endsection

@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
@endsection

@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Edit Room') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('admin.rooms.index') }}">{{ __('Rooms') }}</a></div>
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
                        <h4>{{ __('Edit Room') }}</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.rooms.update', $room->room_id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label>{{ __('Workspace') }}</label>
                                <select name="workspace_id" class="form-control">
                                    <option value="">{{ __('Select Workspace') }}</option>
                                    @foreach ($workspaces as $workspace)
                                        <option value="{{ $workspace->workspace_id }}" {{ old('workspace_id', $room->workspace_id) == $workspace->workspace_id ? 'selected' : '' }}>{{ $workspace->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>{{ __('Name') }}</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $room->name) }}">
                            </div>
                            <div class="form-group">
                                <label>{{ __('Description') }}</label>
                                <textarea name="description" class="form-control">{{ old('description', $room->description) }}</textarea>
                            </div>
                            <div class="form-group">
                                <label>{{ __('Size') }}</label>
                                <input type="text" name="size" class="form-control" value="{{ old('size', $room->size) }}">
                            </div>
                            <div class="form-group">
                                <label>{{ __('Price Per Hour') }}</label>
                                <input type="number" name="price_per_hour" class="form-control" value="{{ old('price_per_hour', $room->price_per_hour) }}" step="0.01">
                            </div>
                            <div class="form-group">
                                <label>{{ __('Capacity') }}</label>
                                <input type="number" name="capacity" class="form-control" value="{{ old('capacity', $room->capacity) }}">
                            </div>
                            <div class="form-group">
                                <label>{{ __('Profile Photo') }}</label>
                                <input type="file" name="profile_photo" class="form-control">
                                @if ($room->profile_photo)
                                    <img src="{{ asset('storage/' . $room->profile_photo) }}" alt="Profile Photo" class="img-thumbnail mt-2" style="max-width: 200px;">
                                @endif
                            </div>
                            <div class="form-group">
                                <label>{{ __('Profile Video') }}</label>
                                <input type="file" name="profile_video" class="form-control">
                                @if ($room->profile_video)
                                    <video src="{{ asset('storage/' . $room->profile_video) }}" controls class="mt-2" style="max-width: 200px;"></video>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>{{ __('Status') }}</label>
                                <select name="status" class="form-control">
                                    <option value="" {{ old('status', $room->status) == '' ? 'selected' : '' }}>{{ __('Select Status') }}</option>
                                    <option value="active" {{ old('status', $room->status) == 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                                    <option value="inactive" {{ old('status', $room->status) == 'inactive' ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
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
