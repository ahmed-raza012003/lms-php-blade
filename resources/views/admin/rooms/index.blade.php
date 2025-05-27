@extends('admin.master_layout')

@section('title')
    <title>{{ __('Rooms') }}</title>
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
                <h1>{{ __('Room List') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></div>
                    <div class="breadcrumb-item">{{ __('Rooms') }}</div>
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
                        <h4>{{ __('All Rooms') }}</h4>
                        <div class="card-header-action">
                            <a href="{{ route('admin.rooms.create') }}" class="btn btn-primary">
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
                                    <th>{{ __('Workspace') }}</th>
                                    <th>{{ __('Price/Hour') }}</th>
                                    <th>{{ __('Capacity') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($rooms as $room)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $room->name }}</td>
                                        <td>{{ $room->workspace->name ?? 'N/A' }}</td>
                                        <td>{{ number_format($room->price_per_hour, 2) }}</td>
                                        <td>{{ $room->capacity }}</td>
                                        <td>
                                            <div class="status-container">
                                                <select class="form-control form-control-sm status-select" data-id="{{ $room->room_id }}">
                                                    <option value="" {{ is_null($room->status) || $room->status == '' ? 'selected' : '' }}>{{ __('Select Status') }}</option>
                                                    <option value="active" {{ $room->status == 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                                                    <option value="inactive" {{ $room->status == 'inactive' ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                                                </select>
                                                <span class="badge {{ $room->status == 'active' ? 'badge-success' : ($room->status == 'inactive' ? 'badge-danger' : 'hidden') }} status-badge">
                                                    {{ $room->status ? ucfirst($room->status) : '' }}
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.rooms.show', $room->room_id) }}" class="btn btn-sm btn-info" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.rooms.edit', $room->room_id) }}" class="btn btn-sm btn-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.rooms.destroy', $room->room_id) }}" method="POST" class="d-inline" onclick="return confirm('Are you sure you want to delete this room?');">
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
                                        <td colspan="7" class="text-center">{{ __('No Rooms Found') }}</td>
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
                var roomId = $select.data('id');
                var status = $select.val();
                var $badge = $select.closest('.status-container').find('.status-badge');

                console.log('Room ID:', roomId, 'Status:', status);

                if (!status) {
                    toastr.error('Please select a valid status.');
                    $select.val('');
                    $badge.addClass('hidden').text('');
                    return;
                }

                $.ajax({
                    url: '{{ route("admin.rooms.update-status") }}',
                    type: 'POST',
                    data: {
                        room_id: roomId,
                        status: status,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        console.log('Success:', response);
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
                        console.error('Error:', xhr.responseText);
                        toastr.error('Error: ' + (xhr.responseJSON?.message || 'Server error.'));
                        $select.val('');
                        $badge.addClass('hidden').text('');
                    }
                });
            });
        });
    </script>
@endsection
