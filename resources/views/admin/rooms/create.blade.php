@extends('admin.master_layout')

@section('title')
    <title>{{ __('Create Room') }}</title>
@endsection

@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
@endsection

@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Create Room') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('admin.rooms.index') }}">{{ __('Rooms') }}</a></div>
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
                        <h4>{{ __('New Room') }}</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.rooms.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label>{{ __('Workspace') }}</label>
                                <select name="workspace_id" class="form-control">
                                    <option value="">{{ __('Select Workspace') }}</option>
                                    @foreach ($workspaces as $workspace)
                                        <option value="{{ $workspace->workspace_id }}" {{ old('workspace_id') == $workspace->workspace_id ? 'selected' : '' }}>{{ $workspace->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>{{ __('Name') }}</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                            </div>
                            <div class="form-group">
                                <label>{{ __('Description') }}</label>
                                <textarea name="description" class="form-control">{{ old('description') }}</textarea>
                            </div>
                            <div class="form-group">
                                <label>{{ __('Size') }}</label>
                                <input type="text" name="size" class="form-control" value="{{ old('size') }}">
                            </div>
                            <div class="form-group">
                                <label>{{ __('Price Per Hour') }}</label>
                                <input type="number" name="price_per_hour" class="form-control" value="{{ old('price_per_hour') }}" step="0.01">
                            </div>
                            <div class="form-group">
                                <label>{{ __('Capacity') }}</label>
                                <input type="number" name="capacity" class="form-control" value="{{ old('capacity') }}">
                            </div>
                            <div class="form-group">
                                <label>{{ __('Profile Photo') }}</label>
                                <input type="file" name="profile_photo" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>{{ __('Profile Video') }}</label>
                                <input type="file" name="profile_video" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>{{ __('Status') }}</label>
                                <select name="status" class="form-control">
                                    <option value="" {{ old('status') == '' ? 'selected' : '' }}>{{ __('Select Status') }}</option>
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>{{ __('Inactive') }}</option>
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
