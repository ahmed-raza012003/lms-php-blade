@extends('admin.master_layout')

@section('title')
    <title>{{ __('Edit Teacher') }}</title>
@endsection

@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <style>
        .credential-section { border: 1px solid #e9ecef; border-radius: 6px; padding: 15px; margin-bottom: 20px; }
        .credential-item { display: flex; align-items: flex-end; gap: 15px; margin-bottom: 15px; }
        .credential-img { max-width: 40px; height: auto; border-radius: 4px; }
        .error { color: #dc3545; font-size: 13px; margin-top: 5px; }
        .flex-1 { flex: 1; }
    </style>
@endsection

@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Edit Teacher') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('admin.teachers.index') }}">{{ __('Teachers') }}</a></div>
                    <div class="breadcrumb-item active">{{ __('Edit') }}</div>
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
                        <h4>{{ __('Update Teacher') }}</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.teachers.update', $teacher->teacher_id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <!-- Left Column: Basic Fields -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('User') }}</label>
                                        <select name="user_id" class="form-control" required>
                                            <option value="">{{ __('Select User') }}</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}" {{ old('user_id', $teacher->user_id) == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                                            @endforeach
                                        </select>
                                        @error('user_id')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('First Name') }}</label>
                                        <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $teacher->first_name) }}" required>
                                        @error('first_name')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('Last Name') }}</label>
                                        <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $teacher->last_name) }}">
                                        @error('last_name')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('Bio') }}</label>
                                        <textarea name="bio" class="form-control" rows="4">{{ old('bio', $teacher->bio) }}</textarea>
                                        @error('bio')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('Subjects') }}</label>
                                        <input type="text" name="subjects" class="form-control" value="{{ old('subjects', $teacher->subjects ? implode(',', $teacher->subjects) : '') }}" placeholder="e.g., Math,Science">
                                        @error('subjects')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('School Levels') }}</label>
                                        <input type="text" name="school_levels" class="form-control" value="{{ old(' -/school_levels', $teacher->school_levels ? implode(',', $teacher->school_levels) : '') }}" placeholder="e.g., High School,Middle School">
                                        @error('school_levels')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('Hourly Rate') }}</label>
                                        <input type="number" name="hourly_rate" class="form-control" value="{{ old('hourly_rate', $teacher->hourly_rate) }}" step="0.01">
                                        @error('hourly_rate')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('Availability') }}</label>
                                        <input type="text" name="availability" class="form-control" value="{{ old('availability', $teacher->availability ? implode(',', $teacher->availability) : '') }}" placeholder="e.g., Monday,Tuesday">
                                        @error('availability')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('Rating') }}</label>
                                        <input type="number" name="rating" class="form-control" value="{{ old('rating', $teacher->rating) }}" step="0.01" min="0" max="5">
                                        @error('rating')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('Payment Info') }}</label>
                                        <input type="text" name="payment_info" class="form-control" value="{{ old('payment_info', $teacher->payment_info) }}">
                                        @error('payment_info')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('Highest Qualification') }}</label>
                                        <input type="text" name="highest_qualification" class="form-control" value="{{ old('highest_qualification', $teacher->highest_qualification) }}">
                                        @error('highest_qualification')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('Years of Experience') }}</label>
                                        <input type="number" name="years_of_experience" class="form-control" value="{{ old('years_of_experience', $teacher->years_of_experience) }}" min="0">
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
                                            @foreach ($teacher->degrees as $index => $degree)
                                                <div class="credential-item">
                                                    <div class="form-group flex-1">
                                                        <label>{{ __('Degree Name') }}</label>
                                                        <input type="text" name="degrees[{{ $index }}][name]" class="form-control" value="{{ old('degrees.' . $index . '.name', $degree->name) }}">
                                                        <input type="hidden" name="degrees[{{ $index }}][id]" value="{{ $degree->id }}">
                                                        @error('degrees.' . $index . '.name')
                                                            <div class="error">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group flex-1">
                                                        <label>{{ __('Upload File (PDF/Image)') }}</label>
                                                        <input type="file" name="degrees[{{ $index }}][file]" class="form-control" accept=".pdf,image/*">
                                                        @if ($degree->file_path)
                                                            <div class="mt-2">
                                                                <small>{{ __('Current') }}:</small>
                                                                @if (pathinfo($degree->file_path, PATHINFO_EXTENSION) == 'pdf')
                                                                    <a href="{{ Storage::url($degree->file_path) }}" target="_blank" class="text-primary">{{ __('View PDF') }}</a>
                                                                @else
                                                                    <img src="{{ Storage::url($degree->file_path) }}" alt="Degree" class="credential-img mt-1">
                                                                @endif
                                                            </div>
                                                        @endif
                                                        @error('degrees.' . $index . '.file')
                                                            <div class="error">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <button type="button" class="btn btn-danger remove-degree-btn">{{ __('Remove') }}</button>
                                                </div>
                                            @endforeach
                                        </div>
                                        <button type="button" id="addDegreeBtn" class="btn btn-outline-primary mt-3"><i class="fas fa-plus"></i> {{ __('Add Degree') }}</button>
                                    </div>

                                    <div class="credential-section">
                                        <h5 class="mb-3">{{ __('Certifications') }}</h5>
                                        <div id="certFields">
                                            @foreach ($teacher->certifications as $index => $cert)
                                                <div class="credential-item">
                                                    <div class="form-group flex-1">
                                                        <label>{{ __('Certification Name') }}</label>
                                                        <input type="text" name="certifications[{{ $index }}][name]" class="form-control" value="{{ old('certifications.' . $index . '.name', $cert->name) }}">
                                                        <input type="hidden" name="certifications[{{ $index }}][id]" value="{{ $cert->id }}">
                                                        @error('certifications.' . $index . '.name')
                                                            <div class="error">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group flex-1">
                                                        <label>{{ __('Upload File (PDF/Image)') }}</label>
                                                        <input type="file" name="certifications[{{ $index }}][file]" class="form-control" accept=".pdf,image/*">
                                                        @if ($cert->file_path)
                                                            <div class="mt-2">
                                                                <small>{{ __('Current') }}:</small>
                                                                @if (pathinfo($cert->file_path, PATHINFO_EXTENSION) == 'pdf')
                                                                    <a href="{{ Storage::url($cert->file_path) }}" target="_blank" class="text-primary">{{ __('View PDF') }}</a>
                                                                @else
                                                                    <img src="{{ Storage::url($cert->file_path) }}" alt="Certification" class="credential-img mt-1">
                                                                @endif
                                                            </div>
                                                        @endif
                                                        @error('certifications.' . $index . '.file')
                                                            <div class="error">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <button type="button" class="btn btn-danger remove-cert-btn">{{ __('Remove') }}</button>
                                                </div>
                                            @endforeach
                                        </div>
                                        <button type="button" id="addCertBtn" class="btn btn-outline-primary mt-3"><i class="fas fa-plus"></i> {{ __('Add Certification') }}</button>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">{{ __('Update Teacher') }}</button>
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

            let degreeIndex = {{ $teacher->degrees->count() }};
            let certIndex = {{ $teacher->certifications->count() }};

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
