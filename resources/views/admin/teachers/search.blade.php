@extends('frontend.layouts.master')

@section('contents')
    <!-- Breadcrumb -->
    <x-frontend.breadcrumb :title="__('Teachers')" :links="[['url' => route('home'), 'text' => __('Home')], ['url' => '', 'text' => __('Teachers')]]" />

    <!-- Teachers Section -->
    <section class="teachers-area" style="padding: 80px 0; background: linear-gradient(to bottom, #f8f9fa, #ffffff);">
        <div class="container">
            <div class="row">
                <!-- Main Content -->
                <div class="col-xl-9 col-lg-8">
                    <!-- Search Form -->
                    <div class="teachers-top-wrap" style="margin-bottom: 30px;">
                        <div class="tgmenu__search d-none d-md-block" style="max-width: 500px; margin: 0 auto;">
                            <form action="{{ route('search_teachers') }}" class="tgmenu__search-form" style="display: flex; align-items: center; background: #fff; border-radius: 50px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); overflow: hidden;">
                                <div class="select-grp" style="padding: 0 15px; display: flex; align-items: center;">
                                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg" style="color: #666;">
                                        <path d="M10.992 13.25C10.5778 13.25 10.242 13.5858 10.242 14C10.242 14.4142 10.5778 14.75 10.992 14.75V13.25ZM16.992 14.75C17.4062 14.75 17.742 14.4142 17.742 14C17.742 13.5858 17.4062 13.25 16.992 13.25V14.75ZM14.742 11C14.742 10.5858 14.4062 10.25 13.992 10.25C13.5778 10.25 13.242 10.5858 13.242 11H14.742ZM13.242 17C13.242 17.4142 13.5778 17.75 13.992 17.75C14.4062 17.75 14.742 17.4142 14.742 17H13.242ZM1 6.4H1.75H1ZM1 1.6H1.75H1ZM6.4 1V1.75V1ZM7 1.6H6.25H7ZM6.4 7V6.25V7ZM1 16.4H1.75H1ZM1 11.6H1.75H1ZM6.4 11V11.75V11ZM7 11.6H6.25H7ZM6.4 17V17.75V17ZM1.6 17V17.75V17ZM11 6.4H11.75H11ZM11 1.6H11.75H11ZM11.6 1V0.25V1ZM16.4 1V1.75V1ZM17 1.6H17.75H17ZM17 6.4H17.75H17ZM16.4 7V6.25V7ZM10.992 14.75H13.992V13.25H10.992V14.75ZM16.992 13.25H13.992V14.75H16.992V13.25ZM14.742 14V11H13.242V14H14.742ZM13.242 14V17H14.742V14H13.242ZM1.75 6.4V1.6H0.25V6.4H1.75ZM1.75 1.6C1.75 1.63978 1.7342 1.67794 1.70607 1.70607L0.645406 0.645406C0.392232 0.89858 0.25 1.24196 0.25 1.6H1.75ZM1.70607 1.70607C1.67794 1.7342 1.63978 1.75 1.6 1.75V0.25C1.24196 0.25 0.89858 0.392232 0.645406 0.645406L1.70607 1.70607ZM1.6 1.75H6.4V0.25H1.6V1.75ZM6.4 1.75C6.36022 1.75 6.32207 1.7342 6.29393 1.70607L7.35459 0.645406C7.10142 0.392231 6.75804 0.25 6.4 0.25V1.75ZM6.29393 1.70607C6.2658 1.67793 6.25 1.63978 6.25 1.6H7.75C7.75 1.24196 7.60777 0.898581 7.35459 0.645406L6.29393 1.70607ZM6.25 1.6V6.4H7.75V1.6H6.25ZM6.25 6.4C6.25 6.36022 6.2658 6.32207 6.29393 6.29393L7.35459 7.35459C7.60777 7.10142 7.75 6.75804 7.75 6.4H6.25ZM6.29393 6.29393C6.32207 6.2658 6.36022 6.25 6.4 6.25V7.75C6.75804 7.75 7.10142 7.60777 7.35459 7.35459L6.29393 6.29393ZM6.4 6.25H1.6V7.75H6.4V6.25ZM1.6 6.25C1.63978 6.25 1.67793 6.2658 1.70607 6.29393L0.645406 7.35459C0.898581 7.60777 1.24196 7.75 1.6 7.75V6.25ZM1.70607 6.29393C1.7342 6.32207 1.75 6.36022 1.75 6.4H0.25C0.25 6.75804 0.392231 7.10142 0.645406 7.35459L1.70607 6.29393ZM1.75 16.4V11.6H0.25V16.4H1.75ZM1.75 11.6C1.75 11.6398 1.7342 11.6779 1.70607 11.7061L0.645406 10.6454C0.392231 10.8986 0.25 11.242 0.25 11.6H1.75ZM1.70607 11.7061C1.67793 11.7342 1.63978 11.75 1.6 11.75V10.25C1.24196 10.25 0.898581 10.3922 0.645406 10.6454L1.70607 11.7061ZM1.6 11.75H6.4V10.25H1.6V11.75ZM6.4 11.75C6.36022 11.75 6.32207 11.7342 6.29393 11.7061L7.35459 10.6454C7.10142 10.3922 6.75804 10.25 6.4 10.25V11.75ZM6.29393 11.7061C6.2658 11.6779 6.25 11.6398 6.25 11.6H7.75C7.75 11.242 7.60777 10.8986 7.35459 10.6454L6.29393 11.7061ZM6.25 11.6V16.4H7.75V11.6H6.25ZM6.25 16.4C6.25 16.3602 6.2658 16.3221 6.29393 16.2939L7.35459 17.3546C7.60777 17.1014 7.75 16.758 7.75 16.4H6.25ZM6.29393 16.2939C6.32207 16.2658 6.36022 16.25 6.4 16.25V17.75C6.75804 17.75 7.10142 17.6078 7.35459 17.3546L6.29393 16.2939ZM6.4 16.25H1.6V17.75H6.4V16.25ZM1.6 16.25C1.63978 16.25 1.67793 16.2658 1.70607 16.2939L0.645406 17.3546C0.898581 17.6078 1.24196 17.75 1.6 17.75V16.25ZM1.70607 16.2939C1.7342 16.3221 1.75 16.3602 1.75 16.4H0.25C0.25 16.758 0.392231 17.1014 0.645406 17.3546L1.70607 16.2939ZM11.75 6.4V1.6H10.25V6.4H11.75ZM11.75 1.6C11.75 1.63978 11.7342 1.67793 11.7061 1.70607L10.6454 0.645406C10.3922 0.898581 10.25 1.24196 10.25 1.6H11.75ZM11.7061 1.70607C11.6779 1.7342 11.6398 1.75 11.6 1.75V0.25C11.242 0.25 10.8986 0.392231 10.6454 0.645406L11.7061 1.70607ZM11.6 1.75H16.4V0.25H11.6V1.75ZM16.4 1.75C16.3602 1.75 16.3221 1.7342 16.2939 1.70607L17.3546 0.645406C17.1014 0.392231 16.758 0.25 16.4 0.25V1.75ZM16.2939 1.70607C16.2658 1.67793 16.25 1.63978 16.25 1.6H17.75C17.75 1.24196 17.6078 0.898581 17.3546 0.645406L16.2939 1.70607ZM16.25 1.6V6.4H17.75V1.6H16.25ZM16.25 6.4C16.25 6.36022 16.2658 6.32207 16.2939 6.29393L17.3546 7.35459C17.6078 7.10142 17.75 6.75804 17.75 6.4H16.25ZM16.2939 6.29393C16.3221 6.2658 16.3602 6.25 16.4 6.25V7.75C16.758 7.75 17.1014 7.60777 17.3546 7.35459L16.2939 6.29393ZM16.4 6.25H11.6V7.75H16.4V6.25ZM11.6 6.25C11.6398 6.25 11.6779 6.2658 11.7061 6.29393L10.6454 7.35459C10.8986 7.60777 11.242 7.75 11.6 7.75V6.25ZM11.7061 6.29393C11.7342 6.32207 11.75 6.36022 11.75 6.4H10.25C10.25 6.75804 10.3922 7.10142 10.6454 7.35459L11.7061 6.29393Z" fill="currentcolor" />
                                    </svg>
                                </div>
                                <div class="input-grp" style="flex: 1; display: flex;">
                                    <input type="text" placeholder="{{ __('Search For Teachers') }} . . ." name="search" value="{{ request('search') }}" style="border: none; padding: 12px 15px; width: 100%; font-size: 16px; outline: none;">
                                    <button type="submit" style="background: #007bff; color: #fff; border: none; padding: 12px 20px; border-radius: 0 50px 50px 0; cursor: pointer; transition: background 0.3s;">
                                        <i class="flaticon-search" style="font-size: 18px;"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- No Results Message -->
                    @if($noResultsMessage)
                        <div class="alert alert-info" style="background: #e7f1ff; border: none; border-radius: 10px; padding: 15px; margin-bottom: 30px; text-align: center; font-size: 16px; color: #1e40af;">
                            {{ $noResultsMessage }}
                        </div>
                    @endif

                    <!-- Teachers Grid -->
                    <div class="row teachers__grid-wrap row-cols-1 row-cols-xl-3 row-cols-lg-2 row-cols-md-2 row-cols-sm-1">
                        @forelse($teachers as $teacher)
                            <div class="col" style="margin-bottom: 30px;">
                                <div class="teachers__item card" style="background: #fff; border: none; border-radius: 15px; box-shadow: 0 8px 25px rgba(0,0,0,0.1); overflow: hidden; transition: transform 0.3s, box-shadow 0.3s; position: relative;">
                                    <div style="width: 100%; height: 200px; background: #f0f4f8; display: flex; justify-content: center; align-items: center; overflow: hidden;">
                                        @if($teacher->profile_photo)
                                            <img src="{{ $teacher->profile_photo }}" alt="{{ $teacher->first_name }} {{ $teacher->last_name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                        @else
                                            <i class="fas fa-user" style="font-size: 80px; color: #6b7280;"></i>
                                        @endif
                                    </div>
                                    <div class="card-body" style="padding: 20px;">
                                        <h5 class="card-title" style="font-size: 20px; font-weight: 600; color: #1f2937; margin-bottom: 15px; text-align: center; background: linear-gradient(to right, #3b82f6, #10b981); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">{{ $teacher->first_name }} {{ $teacher->last_name ?? '' }}</h5>
                                        <p class="card-text" style="font-size: 14px; color: #4b5563; margin-bottom: 10px;"><strong style="color: #1f2937;">Bio:</strong> {{ Str::limit($teacher->bio ?? 'No bio provided', 100) }}</p>
                                        <p class="card-text" style="font-size: 14px; color: #4b5563; margin-bottom: 10px;"><strong style="color: #1f2937;">Subjects:</strong> {{ $teacher->subjects ? implode(', ', $teacher->subjects) : 'None' }}</p>
                                        <p class="card-text" style="font-size: 14px; color: #4b5563; margin-bottom: 10px;"><strong style="color: #1f2937;">School Levels:</strong> {{ $teacher->school_levels ? implode(', ', $teacher->school_levels) : 'None' }}</p>
                                        <p class="card-text" style="font-size: 14px; color: #4b5563; margin-bottom: 10px;"><strong style="color: #1f2937;">Hourly Rate:</strong> ${{ number_format($teacher->hourly_rate ?? 0, 2) }}</p>
                                        <p class="card-text" style="font-size: 14px; color: #4b5563; margin-bottom: 10px;"><strong style="color: #1f2937;">Rating:</strong> {{ $teacher->rating ? number_format($teacher->rating, 2) . '/5' : 'Not rated' }}</p>
                                        <p class="card-text" style="font-size: 14px; color: #4b5563; margin-bottom: 10px;"><strong style="color: #1f2937;">Qualification:</strong> {{ $teacher->highest_qualification ?? 'Not specified' }}</p>
                                        <p class="card-text" style="font-size: 14px; color: #4b5563; margin-bottom: 20px;"><strong style="color: #1f2937;">Experience:</strong> {{ $teacher->years_of_experience ?? 0 }} years</p>
                                        <button class="btn btn-secondary select-teacher" disabled style="display: block; width: 100%; padding: 12px; background: linear-gradient(to right, #6b7280, #4b5563); border: none; border-radius: 10px; color: #fff; font-size: 16px; font-weight: 500; cursor: not-allowed; transition: background 0.3s;">Select Teacher</button>
                                    </div>
                                    <div style="position: absolute; top: 10px; right: 10px; background: #10b981; color: #fff; padding: 5px 10px; border-radius: 5px; font-size: 12px;">Verified</div>
                                </div>
                            </div>
                        @empty
                            <div class="col" style="text-align: center; padding: 50px 0;">
                                <p style="font-size: 18px; color: #6b7280;">No teachers available.</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    <div class="pagination-wrap" style="margin-top: 40px; text-align: center;">
                        {{ $teachers->links() }}
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-xl-3 col-lg-4">
                    <div class="courses__sidebar_area" style="background: #fff; border-radius: 15px; padding: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                        <aside class="courses__sidebar">
                            <div class="courses-widget">
                                <h4 class="widget-title" style="font-size: 20px; font-weight: 600; color: #1f2937; margin-bottom: 20px;">Filters</h4>
                                <p style="font-size: 16px; color: #4b5563;">Filters coming soon.</p>
                            </div>
                        </aside>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection