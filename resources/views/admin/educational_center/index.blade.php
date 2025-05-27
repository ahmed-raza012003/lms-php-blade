```blade
@extends('admin.master_layout')

@section('title')
    <title>{{ __('Create Educational Center') }}</title>
@endsection

@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Educational Center List') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></div>
                    <div class="breadcrumb-item">{{ __('Create Educational Center') }}</div>
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
                        <h4>{{ __('All Educational Centers') }}</h4>
                        <div class="card-header-action">
                            <a href="{{ route('admin.educenter.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> {{ __('Add New Educational Center') }}
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('ID') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Address') }}</th>
                                    <th>{{ __('Operating Hours') }}</th>
                                    <th>{{ __('Verification Status') }}</th>
                                    <th>{{ __('Owner') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($centers as $center)
                                    <tr>
                                        <td>{{ $center->id }}</td>
                                        <td>{{ $center->name }}</td>
                                        <td>{{ $center->address }}</td>
                                        <td>{{ $center->operating_hours ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge {{ $center->verification_status == 'approved' ? 'badge-success' : ($center->verification_status == 'rejected' ? 'badge-danger' : 'badge-warning') }}">
                                                {{ __($center->verification_status) }}
                                            </span>
                                        </td>
                                        <td>{{ $center->owner->name ?? 'N/A' }}</td>
                                        <td>
                                            <a href="{{ route('admin.educenter.edit', $center->id) }}" class="btn btn-sm btn-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.educenter.destroy', $center->id) }}" method="POST" class="d-inline" onclick="return confirm('Are you sure you want to delete this center?');">
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
                                        <td colspan="7" class="text-center">{{ __('No Educational Centers Found') }}</td>
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
```
