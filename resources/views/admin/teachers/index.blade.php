@extends('admin.master_layout')

@section('title')
    <title>{{ __('Teachers') }}</title>
@endsection

@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <style>
        .credential-img { max-width: 40px; height: auto; border-radius: 4px; }
        .action-btn { padding: 6px 12px; font-size: 14px; }
    </style>
@endsection

@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Teachers') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></div>
                    <div class="breadcrumb-item active">{{ __('Teachers') }}</div>
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
                        <h4>{{ __('All Teachers') }}</h4>
                        <div class="card-header-action">
                            <a href="{{ route('admin.teachers.create') }}" class="btn btn-primary action-btn">
                                <i class="fas fa-plus"></i> {{ __('Add Teacher') }}
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>{{ __('Sr No.') }}</th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Email') }}</th>
                                        <th>{{ __('Degrees') }}</th>
                                        <th>{{ __('Certifications') }}</th>
                                        <th>{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($teachers as $teacher)
                                        <tr>
                                            <td>{{ $teachers->firstItem() + $loop->index }}</td>
                                            <td>{{ $teacher->first_name }} {{ $teacher->last_name }}</td>
                                            <td>{{ $teacher->user->email ?? 'N/A' }}</td>
                                            <td>
                                                @if ($teacher->degrees->isNotEmpty())
                                                    <ul class="list-unstyled mb-0">
                                                        @foreach ($teacher->degrees as $degree)
                                                            <li class="mb-1">
                                                                {{ $degree->name }}
                                                                @if ($degree->file_path)
                                                                    @if (pathinfo($degree->file_path, PATHINFO_EXTENSION) == 'pdf')
                                                                        <a href="{{ Storage::url($degree->file_path) }}" target="_blank" class="ml-2 text-primary"><i class="fas fa-file-pdf"></i></a>
                                                                    @else
                                                                        <img src="{{ Storage::url($degree->file_path) }}" alt="Degree" class="credential-img ml-2">
                                                                    @endif
                                                                @endif
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    {{ __('N/A') }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($teacher->certifications->isNotEmpty())
                                                    <ul class="list-unstyled mb-0">
                                                        @foreach ($teacher->certifications as $cert)
                                                            <li class="mb-1">
                                                                {{ $cert->name }}
                                                                @if ($cert->file_path)
                                                                    @if (pathinfo($cert->file_path, PATHINFO_EXTENSION) == 'pdf')
                                                                        <a href="{{ Storage::url($cert->file_path) }}" target="_blank" class="ml-2 text-primary"><i class="fas fa-file-pdf"></i></a>
                                                                    @else
                                                                        <img src="{{ Storage::url($cert->file_path) }}" alt="Certification" class="credential-img ml-2">
                                                                    @endif
                                                                @endif
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    {{ __('N/A') }}
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('admin.teachers.show', $teacher->teacher_id) }}" class="btn btn-success action-btn" title="{{ __('View') }}">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.teachers.edit', $teacher->teacher_id) }}" class="btn btn-primary action-btn" title="{{ __('Edit') }}">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.teachers.destroy', $teacher->teacher_id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('Are you sure you want to delete this teacher?') }}');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger action-btn" title="{{ __('Delete') }}">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">{{ __('No Teachers Found') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        {{ $teachers->links() }}
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
        });
    </script>
@endsection
