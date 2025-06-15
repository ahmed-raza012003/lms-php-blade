
@extends('admin.master_layout')

@section('title')
    <title>{{ __('Students') }}</title>
@endsection

@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <style>
        .status-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .status-select {
            width: 140px;
        }
        .status-badge {
            padding: 5px 10px;
            font-size: 12px;
        }
        .hidden {
            display: none;
        }
    </style>
@endsection

@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Student List') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></div>
                    <div class="breadcrumb-item">{{ __('Students') }}</div>
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
                        <button type="button" status-badge" class="close" data-dismiss="alert">×</button>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="card">
                    <div class="card-header">
                        <h4>{{ __('All Students') }}</h4>
                        <div class="card-header-action">
                            <a href="{{ route('admin.students.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> {{ __('Create New') }}
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('Sr No.') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Grade Level') }}</th>
                                    <th>{{ __('Parent') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($students as $student)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $student->user->name ?? 'N/A' }}</td>
                                        <td>{{ $student->grade_level ?? 'N/A' }}</td>
                                        <td>{{ $student->parent->user->name ?? 'N/A' }}</td>
                                        <td>
                                            <div class="status-container">
                                                <select class="form-control form-control-sm status-select" data-id="{{ $student->student_id }}">
                                                    <option value="" {{ is_null($student->status) || $student->status == '' ? 'selected' : '' }}>{{ __('Select Status') }}</option>
                                                    <option value="active" {{ $student->status == 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                                                    <option value="inactive" {{ $student->status == 'inactive' ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                                                </select>
                                                <span class="badge {{ $student->status == 'active' ? 'badge-success' : ($student->status == 'inactive' ? 'badge-danger' : 'hidden') }} status-badge">
                                                    {{ $student->status ? ucfirst($student->status) : '' }}
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.students.show', $student->student_id) }}" class="btn btn-sm btn-info" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.students.edit', $student->student_id) }}" class="btn btn-sm btn-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.students.destroy', $student->student_id) }}" method="POST" class="d-inline" onclick="return confirm('Are you sure you want to delete this student?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">{{ __('No Students Found') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        {{ $students->links() }}
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.status-select').on('change', function () {
                var $select = $(this);
                var studentId = $select.data('id');
                var status = $select.val();
                var $badge = $select.closest('.status-container').find('.status-badge');

                if (!status) {
                    toastr.error('Please select a valid status.');
                    $select.val('');
                    $badge.addClass('hidden').text('');
                    return;
                }

                $.ajax({
                    url: '{{ route("admin.students.update-status") }}',
                    type: 'POST',
                    data: {
                        student_id: studentId,
                        status: status,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        if (response.success) {
                            toastr.success(response.message);
                            $badge.text(status.charAt(0).toUpperCase() + status.slice(1));
                            $badge.removeClass('hidden badge-success badge-danger');
                            $badge.addClass(status === 'active' ? 'badge-success' : 'badge-danger');
                        } else {
                            toastr.error(response.message);
                            $select.val(response.current_status || '');
                            $badge.addClass('hidden').text('');
                        }
                    },
                    error: function (xhr) {
                        toastr.error('Error: ' + (xhr.responseJSON?.message || 'Server error.'));
                        $select.val('');
                        $badge.addClass('hidden').text('');
                    }
                });
            });
        });
    </script>
@endsection
