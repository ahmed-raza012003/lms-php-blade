@extends('admin.master_layout')

@section('title')
    <title>{{ __('Assign Assistant') }}</title>
@endsection

@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
@endsection

@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Assign Assistant') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.assistants.index') }}">{{ __('Dashboard') }}</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('admin.assistants.index') }}">{{ __('Assistants') }}</a></div>
                    <div class="breadcrumb-item">{{ __('Assign') }}</div>
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
                        <h4>{{ __('New Assistant Assignment') }}</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.assistants.store') }}" method="POST" id="assistantForm">
                            @csrf
                            <div class="form-group">
                                <label>{{ __('Assistant') }}</label>
                                <select name="assistant_user_id" id="assistant_user_id" class="form-control @error('assistant_user_id') is-invalid @enderror" required>
                                    <option value="">{{ __('Select Assistant') }}</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" {{ old('assistant_user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                @error('assistant_user_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>{{ __('User') }}</label>
                                <select name="user_id" id="user_id" class="form-control @error('user_id') is-invalid @enderror" required>
                                    <option value="">{{ __('Select User') }}</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>{{ __('Authority Level') }}</label>
                                <select name="authority_level" id="authority_level" class="form-control @error('authority_level') is-invalid @enderror" required>
                                    <option value="limited" {{ old('authority_level') == 'limited' ? 'selected' : '' }}>{{ __('Limited') }}</option>
                                    <option value="full" {{ old('authority_level') == 'full' ? 'selected' : '' }}>{{ __('Full') }}</option>
                                </select>
                                @error('authority_level')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                            <a href="{{ route('admin.assistants.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        document.getElementById('assistantForm').addEventListener('submit', function(event) {
            var assistantUserId = document.getElementById('assistant_user_id').value;
            var userId = document.getElementById('user_id').value;
            if (!assistantUserId || !userId) {
                event.preventDefault();
                alert('Please select both an assistant and a user.');
            } else if (assistantUserId === userId) {
                event.preventDefault();
                alert('A user cannot be their own assistant.');
            }
        });
    </script>
@endsection