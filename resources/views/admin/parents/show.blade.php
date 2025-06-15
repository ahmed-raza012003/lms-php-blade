@extends('admin.master_layout')

@section('title')
    <title>{{ __('Parent Details') }}</title>
@endsection

@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
@endsection

@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Parent Details') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.parents.index') }}">{{ __('Dashboard') }}</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('admin.parents.index') }}">{{ __('Parents') }}</a></div>
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
                <div class="card">
                    <div class="card-header">
                        <h4>{{ __('Parent Information') }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>{{ __('ID') }}:</strong> {{ $parent->parent_id }}</p>
                                <p><strong>{{ __('User') }}:</strong> {{ $parent->user->name }}</p>
                                <p><strong>{{ __('Contact Phone') }}:</strong> {{ $parent->contact_phone ?? 'N/A' }}</p>
                                <p><strong>{{ __('Payment Info') }}:</strong> {{ $parent->payment_info ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>{{ __('Bio') }}:</strong> {{ $parent->bio ?? 'N/A' }}</p>
                                <p><strong>{{ __('Profile Photo') }}:</strong>
                                    @if ($parent->profile_photo)
                                        <img src="{{ asset('storage/' . $parent->profile_photo) }}" alt="Profile Photo" style="max-height: 100px;">
                                    @else
                                        N/A
                                    @endif
                                </p>
                                {{-- <p><strong>{{ __('Students') }}:</strong>
                                    @if ($parent->students->isNotEmpty())
                                        <ul>
                                            @foreach ($parent->students as $student)
                                                <li>{{ $student->name }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        N/A
                                    @endif
                                </p> --}}
                            </div>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('admin.parents.edit', $parent->parent_id) }}" class="btn btn-warning">{{ __('Edit') }}</a>
                            <a href="{{ route('admin.parents.index') }}" class="btn btn-secondary">{{ __('Back') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
@endsection