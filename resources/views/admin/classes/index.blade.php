@extends('admin.master_layout')

@section('title')
    <title>{{ __('Classes') }}</title>
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
                <h1>{{ __('Classes') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></div>
                    <div class="breadcrumb-item active">{{ __('Classes') }}</div>
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
                        <h4>{{ __('All Classes') }}</h4>
                        <div class="card-header-action">
                            <a href="{{ route('admin.classes.create') }}" class="btn btn-primary">{{ __('Add New Class') }}</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>{{ __('Subject') }}</th>
                                        <th>{{ __('Workspace') }}</th>
                                        <th>{{ __('Teacher') }}</th>
                                        <th>{{ __('Room') }}</th>
                                        {{-- <th>{{ __('School Level') }}</th> --}}
                                        <th>{{ __('Price') }}</th>
                                        <th>{{ __('Start Time') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($classes as $class)
                                        <tr>
                                            <td>{{ $class->subject }}</td>
                                            <td>{{ $class->workspace->name ?? 'Unknown Workspace' }}</td>
                                            <td>{{ $class->teacher ? ($class->teacher->name ?? $class->teacher->user->name ?? 'Unknown Teacher') : 'Unknown Teacher' }}</td>
                                            <td>{{ $class->room->name ?? 'Unknown Room' }}</td>
                                            {{-- <td>{{ $class->school_level }}</td> --}}
                                            <td>{{ number_format($class->price, 2) }}</td>
                                            <td>{{ $class->start_time->format('d M Y H:i') }}</td>
                                            <td>{{ ucfirst($class->status) }}</td>
                                            <td>
                                                <a href="{{ route('admin.classes.show', $class->class_id) }}" class="btn btn-info btn-sm">{{ __('View') }}</a>
                                                <a href="{{ route('admin.classes.edit', $class->class_id) }}" class="btn btn-primary btn-sm">{{ __('Edit') }}</a>
                                                <form action="{{ route('admin.classes.destroy', $class->class_id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('{{ __('Are you sure you want to delete this class?') }}')">{{ __('Delete') }}</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center">
                            {{ $classes->links() }}
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