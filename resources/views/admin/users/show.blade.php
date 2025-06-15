@extends('admin.master_layout')

@section('title')
    <title>{{ __('User Details') }}</title>
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
                <h1>{{ __('User Details') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">{{ __('Users') }}</a></div>
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
                        <h4>{{ __('User Details') }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>{{ __('ID') }}</label>
                            <p class="form-control-static">{{ $user->id }}</p>
                        </div>
                        <div class="form-group">
                            <label>{{ __('Name') }}</label>
                            <p class="form-control-static">{{ $user->name }}</p>
                        </div>
                        <div class="form-group">
                            <label>{{ __('Email') }}</label>
                            <p class="form-control-static">{{ $user->email }}</p>
                        </div>
                        <div class="form-group">
                            <label>{{ __('Role') }}</label>
                            <p class="form-control-static">{{ ucfirst($user->role ?? 'N/A') }}</p>
                        </div>
                        <div class="form-group">
                            <label>{{ __('Created At') }}</label>
                            <p class="form-control-static">{{ $user->created_at->format('Y-m-d H:i:s') }}</p>
                        </div>
                        <div class="form-group">
                            <label>{{ __('Updated At') }}</label>
                            <p class="form-control-static">{{ $user->updated_at->format('Y-m-d H:i:s') }}</p>
                        </div>
                        <div class="form-group">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary">{{ __('Edit') }}</a>
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('{{ __('Are you sure you want to delete this user?') }}')">{{ __('Delete') }}</button>
                            </form>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">{{ __('Back') }}</a>
                        </div>
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