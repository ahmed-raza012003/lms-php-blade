@extends('admin.master_layout')

@section('title')
    <title>{{ __('Create Teacher') }}</title>
@endsection

@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <style>
        .credential-section { border: 1px solid #e9ecef; border-radius: 6px; padding: 15px; margin-bottom: 20px; }
        .credential-item { display: flex; align-items: flex-end; gap: 15px; margin-bottom: 15px; }
        .error { color: #dc3545; font-size: 13px; margin-top: 5px; }
        .flex-1 { flex: 1; }
    </style>
@endsection

@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Create Teacher') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('admin.teachers.index') }}">{{ __('Teachers') }}</a></div>
                    <div class="breadcrumb-item active">{{ __('Create') }}</div>
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
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card">
                    <div class="card-header">
                        <h4>{{ __('Add New Teacher') }}</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.teachers.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <!-- Left Column: Basic Fields -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('User') }}</label>
                                        <select name="user_id" class="form-control" required>
                                            <option value="">{{ __('Select User') }}</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                                            @endforeach
                                        </select>
                                        @error('user_id')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('First Name') }}</label>
                                        <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}" required>
                                        @error('first_name')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('Last Name') }}</label>
                                        <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}">
                                        @error('last_name')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('Bio') }}</label>
                                        <textarea name="bio" class="form-control" rows="4">{{ old('bio') }}</textarea>
                                        @error('bio')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('Subjects') }}</label>
                                        <input type="text" name="subjects" class="form-control" value="{{ old('subjects') }}" placeholder="e.g., Math,Science">
                                        @error('subjects')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('School Levels') }}</label>
                                        <input type="text" name="school_levels" class="form-control" value="{{ old('school_levels') }}" placeholder="e.g., High School,Middle School">
                                        @error('school_levels')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('Hourly Rate') }}</label>
                                        <input type="number" name="hourly_rate" class="form-control" value="{{ old('hourly_rate') }}" step="0.01">
                                        @error('hourly_rate')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('Availability') }}</label>
                                        <input type="text" name="availability" class="form-control" value="{{ old('availability') }}" placeholder="e.g., Monday,Tuesday">
                                        @error('availability')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('Rating') }}</label>
                                        <input type="number" name="rating" class="form-control" value="{{ old('rating') }}" step="0.01" min="0" max="5">
                                        @error('rating')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('Payment Info') }}</label>
                                        <input type="text" name="payment_info" class="form-control" value="{{ old('payment_info') }}">
                                        @error('payment_info')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('Highest Qualification') }}</label>
                                        <input type="text" name="highest_qualification" class="form-control" value="{{ old('highest_qualification') }}">
                                        @error('highest_qualification')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('Years of Experience') }}</label>
                                        <input type="number" name="years_of_experience" class="form-control" value="{{ old('years_of_experience') }}" min="0">
                                        @error('years_of_experience')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Right Column: Degrees and Certifications -->
                                <div class="col-md-6">
                                    <div class="credential-section">
                                        <h5 class="mb-3">{{ __('Degrees') }}</h5>
                                        <div id="degreeFields">
                                            <div class="credential-item">
                                                <div class="form-group flex-1">
                                                    <label>{{ __('Degree Name') }}</label>
                                                    <input type="text" name="degrees[0][name]" class="form-control" value="{{ old('degrees.0.name') }}">
                                                    @error('degrees.0.name')
                                                        <div class="error">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="form-group flex-1">
                                                    <label>{{ __('Upload File (PDF/Image)') }}</label>
                                                    <input type="file" name="degrees[0][file]" class="form-control" accept=".pdf,image/*">
                                                    @error('degrees.0.file')
                                                        <div class="error">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <button type="button" class="btn btn-danger remove-degree-btn">{{ __('Remove') }}</button>
                                            </div>
                                        </div>
                                        <button type="button" id="addDegreeBtn" class="btn btn-outline-primary mt-3"><i class="fas fa-plus"></i> {{ __('Add Degree') }}</button>
                                    </div>

                                    <div class="credential-section">
                                        <h5 class="mb-3">{{ __('Certifications') }}</h5>
                                        <div id="certFields">
                                            <div class="credential-item">
                                                <div class="form-group flex-1">
                                                    <label>{{ __('Certification Name') }}</label>
                                                    <input type="text" name="certifications[0][name]" class="form-control" value="{{ old('certifications.0.name') }}">
                                                    @error('certifications.0.name')
                                                        <div class="error">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="form-group flex-1">
                                                    <label>{{ __('Upload File (PDF/Image)') }}</label>
                                                    <input type="file" name="certifications[0][file]" class="form-control" accept=".pdf,image/*">
                                                    @error('certifications.0.file')
                                                        <div class="error">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <button type="button" class="btn btn-danger remove-cert-btn">{{ __('Remove') }}</button>
                                            </div>
                                        </div>
                                        <button type="button" id="addCertBtn" class="btn btn-outline-primary mt-3"><i class="fas fa-plus"></i> {{ __('Add Certification') }}</button>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">{{ __('Save Teacher') }}</button>
                                <a href="{{ route('admin.teachers.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                            </div>
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
        $(document).ready(function() {
            toastr.options = { closeButton: true, progressBar: true, positionClass: 'toast-top-right', timeOut: 5000 };

            let degreeIndex = 1;
            let certIndex = 1;

            $('#addDegreeBtn').click(function() {
                $('#degreeFields').append(`
                    <div class="credential-item">
                        <div class="form-group flex-1">
                            <label>{{ __('Degree Name') }}</label>
                            <input type="text" name="degrees[${degreeIndex}][name]" class="form-control">
                        </div>
                        <div class="form-group flex-1">
                            <label>{{ __('Upload File (PDF/Image)') }}</label>
                            <input type="file" name="degrees[${degreeIndex}][file]" class="form-control" accept=".pdf,image/*">
                        </div>
                        <button type="button" class="btn btn-danger remove-degree-btn">{{ __('Remove') }}</button>
                    </div>`);
                degreeIndex++;
            });

            $('#addCertBtn').click(function() {
                $('#certFields').append(`
                    <div class="credential-item">
                        <div class="form-group flex-1">
                            <label>{{ __('Certification Name') }}</label>
                            <input type="text" name="certifications[${certIndex}][name]" class="form-control">
                        </div>
                        <div class="form-group flex-1">
                            <label>{{ __('Upload File (PDF/Image)') }}</label>
                            <input type="file" name="certifications[${certIndex}][file]" class="form-control" accept=".pdf,image/*">
                        </div>
                        <button type="button" class="btn btn-danger remove-cert-btn">{{ __('Remove') }}</button>
                    </div>`);
                certIndex++;
            });

            $(document).on('click', '.remove-degree-btn', function() {
                $(this).closest('.credential-item').remove();
            });

            $(document).on('click', '.remove-cert-btn', function() {
                $(this).closest('.credential-item').remove();
            });
        });
    </script>
@endsection
