@extends('admin.master_layout')

@section('title')
    <title>{{ __('Parents') }}</title>
@endsection

@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
@endsection

@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Parents') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.parents.index') }}">{{ __('Dashboard') }}</a></div>
                    <div class="breadcrumb-item">{{ __('Parents') }}</div>
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
                        <h4>{{ __('All Parents') }}</h4>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('admin.parents.create') }}" class="btn btn-primary mb-3">{{ __('Add New Parent') }}</a>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>{{ __('ID') }}</th>
                                    <th>{{ __('User') }}</th>
                                    <th>{{ __('Contact Phone') }}</th>
                                    <th>{{ __('Payment Info') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($parents as $parent)
                                    <tr>
                                        <td>{{ $parent->parent_id }}</td>
                                        <td>{{ $parent->user->name }}</td>
                                        <td>{{ $parent->contact_phone ?? 'N/A' }}</td>
                                        <td>{{ $parent->payment_info ?? 'N/A' }}</td>
                                        <td>
                                            <a href="{{ route('admin.parents.show', $parent->parent_id) }}" class="btn btn-info btn-sm">{{ __('View') }}</a>
                                            <a href="{{ route('admin.parents.edit', $parent->parent_id) }}" class="btn btn-warning btn-sm">{{ __('Edit') }}</a>
                                            <form action="{{ route('admin.parents.destroy', $parent->parent_id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('{{ __('Are you sure?') }}')">{{ __('Delete') }}</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">{{ __('No parents found') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        {{ $parents->links() }}
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
@endsection