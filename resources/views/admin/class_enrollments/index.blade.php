@extends('admin.master_layout')

@section('title')
    <title>{{ __('Class Enrollments') }}</title>
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
                <h1>{{ __('Class Enrollments') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></div>
                    <div class="breadcrumb-item active">{{ __('Class Enrollments') }}</div>
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
                        <h4>{{ __('All Enrollments') }}</h4>
                        <div class="card-header-action">
                            <a href="{{ route('admin.class-enrollments.create') }}" class="btn btn-primary">{{ __('Add New Enrollment') }}</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>{{ __('Class') }}</th>
                                        <th>{{ __('Student') }}</th>
                                        <th>{{ __('Enrollment Date') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($enrollments as $enrollment)
                                        <tr>
                                            <td>{{ $enrollment->class->subject ?? 'Unknown Class' }}</td>
                                            <td>{{ $enrollment->student->name ?? 'Unknown Student' }}</td>
                                            <td>{{ $enrollment->enrollment_date->format('d M Y H:i') }}</td>
                                            <td>{{ ucfirst($enrollment->status) }}</td>
                                            <td>
                                                <a href="{{ route('admin.class-enrollments.show', $enrollment->enrollment_id) }}" class="btn btn-info btn-sm">{{ __('View') }}</a>
                                                <a href="{{ route('admin.class-enrollments.edit', $enrollment->enrollment_id) }}" class="btn btn-primary btn-sm">{{ __('Edit') }}</a>
                                                <form action="{{ route('admin.class-enrollments.destroy', $enrollment->enrollment_id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('{{ __('Are you sure you want to delete this enrollment?') }}')">{{ __('Delete') }}</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center">
                            {{ $enrollments->links() }}
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
