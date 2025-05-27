@extends('admin.master_layout')

@section('title')
    <title>{{ __('Workspaces') }}</title>
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
                <h1>{{ __('Workspace List') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></div>
                    <div class="breadcrumb-item">{{ __('Workspaces') }}</div>
                </div>
            </div>

            <div class="section-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        {{ session('success') }}
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
                        <h4>{{ __('All Workspaces') }}</h4>
                        <div class="card-header-action">
                            <a href="{{ route('admin.workspace.create') }}" class="btn btn-primary">
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
                                    <th>{{ __('Type') }}</th>
                                    <th>{{ __('City') }}</th>
                                    <th>{{ __('Country') }}</th>
                                    <th>{{ __('Owner') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($workspaces as $workspace)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $workspace->name }}</td>
                                        <td>{{ $workspace->workspace_type }}</td>
                                        <td>{{ $workspace->city }}</td>
                                        <td>{{ $workspace->country }}</td>
                                        <td>{{ $workspace->user->name ?? 'N/A' }}</td>
                                        <td>
                                            <div class="status-container">
                                                <select class="form-control form-control-sm status-select" data-id="{{ $workspace->workspace_id }}">
                                                    <option value="" {{ is_null($workspace->status) || $workspace->status == '' ? 'selected' : '' }}>{{ __('Select Status') }}</option>
                                                    <option value="active" {{ $workspace->status == 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                                                    <option value="inactive" {{ $workspace->status == 'inactive' ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                                                </select>
                                                <span class="badge {{ $workspace->status == 'active' ? 'badge-success' : ($workspace->status == 'inactive' ? 'badge-danger' : 'hidden') }} status-badge">
                                                    {{ $workspace->status ? ucfirst($workspace->status) : '' }}
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.workspace.edit', $workspace->workspace_id) }}" class="btn btn-sm btn-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.workspace.destroy', $workspace->workspace_id) }}" method="POST" class="d-inline" onclick="return confirm('Are you sure you want to delete this workspace?');">
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
                                        <td colspan="8" class="text-center">{{ __('No Workspaces Found') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
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
                var workspaceId = $select.data('id');
                var status = $select.val();
                var $badge = $select.closest('.status-container').find('.status-badge');

                console.log('Selected Workspace ID:', workspaceId, 'Status:', status); // Debug

                if (!status) {
                    toastr.error('Please select a valid status.');
                    $select.val('');
                    $badge.addClass('hidden').text('');
                    return;
                }

                $.ajax({
                    url: '{{ route("admin.workspace.update-status") }}',
                    type: 'POST',
                    data: {
                        workspace_id: workspaceId,
                        status: status,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        console.log('AJAX Success:', response); // Debug
                        if (response.success) {
                            toastr.success(response.message);
                            $badge.text(status.charAt(0).toUpperCase() + status.slice(1));
                            $badge.removeClass('hidden badge-success badge-danger');
                            $badge.addClass(status === 'active' ? 'badge-success' : 'badge-danger');
                        } else {
                            toastr.error(response.message || 'Failed to update status.');
                            $select.val(response.current_status || '');
                            $badge.addClass('hidden').text('');
                        }
                    },
                    error: function (xhr) {
                        console.error('AJAX Error:', xhr.responseText); // Debug
                        toastr.error('Error updating status: ' + (xhr.responseJSON?.message || 'Server error.'));
                        $select.val('');
                        $badge.addClass('hidden').text('');
                    }
                });
            });
        });
    </script>
@endsection
