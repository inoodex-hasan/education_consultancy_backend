@extends('admin.layouts.master')

@section('title', 'Data Submit - Marketing')

@push('styles')
    <style>
        .select2-container .select2-selection--single {
            height: 42px !important;
            border: 1px solid #e0e6ed !important;
            border-radius: 6px !important;
            padding: 6px 12px !important;
            display: flex;
            align-items: center;
            background-color: #fff;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #0e1726;
            font-size: 14px;
            line-height: normal;
            padding-left: 0;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 100%;
            right: 10px;
        }

        .select2-dropdown {
            border-radius: 6px !important;
            border: 1px solid #e0e6ed !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            background-color: #fff;
            z-index: 9999;
        }

        .select2-search__field {
            padding: 6px !important;
            border-radius: 4px !important;
            border: 1px solid #e0e6ed !important;
            background-color: #fff !important;
            color: #0e1726 !important;
        }

        .select2-results__option {
            padding: 8px 12px;
            font-size: 14px;
        }

        .select2-results__option--highlighted {
            background-color: #4361ee !important;
            color: #fff !important;
        }

        .select2-results__option--selected {
            background-color: #e0e6ed !important;
            color: #0e1726 !important;
        }

        /* Dark mode support */
        .dark .select2-container .select2-selection--single {
            background-color: #1b2e4b !important;
            border-color: #253b5c !important;
        }

        .dark .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #bfc9d4 !important;
        }

        .dark .select2-dropdown {
            background-color: #1b2e4b !important;
            border-color: #253b5c !important;
            color: #bfc9d4 !important;
        }

        .dark .select2-search__field {
            background-color: #121e32 !important;
            border-color: #253b5c !important;
            color: #bfc9d4 !important;
        }

        .dark .select2-results__option--selected {
            background-color: #253b5c !important;
            color: #fff !important;
        }

        .dark .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #888ea8;
        }
    </style>
@endpush

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Data Submit (Primary)</h2>
        <a href="{{ route('admin.marketing.leads.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to List
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.marketing.leads.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div class="form-group">
                    <label for="student_name">Student Name <span class="text-danger">*</span></label>
                    <input type="text" name="student_name" id="student_name" class="form-input" required
                        value="{{ old('student_name') }}" />
                    @error('student_name') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="phone">Phone/WhatsApp <span class="text-danger">*</span></label>
                    <input type="text" name="phone" id="phone" class="form-input" required value="{{ old('phone') }}" />
                    @error('phone') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-input" value="{{ old('email') }}" />
                    @error('email') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="source">Contact Source</label>
                    <select name="source" id="source" class="form-select">
                        <option value="Phone" {{ old('source') == 'Phone' ? 'selected' : '' }}>Phone Call</option>
                        <option value="Message" {{ old('source') == 'Message' ? 'selected' : '' }}>WhatsApp/SMS</option>
                        <option value="Messenger" {{ old('source') == 'Messenger' ? 'selected' : '' }}>FB Messenger</option>
                        <option value="Online Chat" {{ old('source') == 'Online Chat' ? 'selected' : '' }}>Website Chat
                        </option>
                        <option value="Walk-in" {{ old('source') == 'Walk-in' ? 'selected' : '' }}>Walk-in</option>
                    </select>
                    @error('source') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="current_education">Current Education</label>
                    <input type="text" name="current_education" id="current_education" class="form-input"
                        value="{{ old('current_education') }}" />
                    @error('current_education') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="preferred_country">Preferred Country</label>
                    <select name="preferred_country" id="preferred_country" class="form-select select2">
                        <option value="">Select Country</option>
                        @foreach ($countries as $country)
                            <option value="{{ $country->id }}" {{ old('preferred_country') == $country->id ? 'selected' : '' }}>
                                {{ $country->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('preferred_country') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="preferred_university">Preferred University</label>
                    <select name="preferred_university" id="preferred_university" class="form-select select2">
                        <option value="">Select University</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="preferred_course">Preferred Course</label>
                    <select name="preferred_course" id="preferred_course" class="form-select select2">
                        <option value="">Select Course</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}" {{ old('preferred_course') == $course->id ? 'selected' : '' }}>
                                {{ $course->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('preferred_course') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="priority">Priority</label>
                    <select name="priority" id="priority" class="form-select">
                        <option value="low" {{ old('priority', 'low') == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                    </select>
                    @error('priority') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="next_follow_up_at">Next Follow-up Date</label>
                    <input type="date" name="next_follow_up_at" id="next_follow_up_at" class="form-input"
                        value="{{ old('next_follow_up_at') }}" />
                    @error('next_follow_up_at') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-group mt-5">
                <label for="notes">Notes/Remarks</label>
                <p class="mb-2 text-xs text-white-dark">If you set a follow-up date, this note will be saved in the follow-up history.</p>
                <textarea name="notes" id="notes" class="form-textarea" rows="4">{{ old('notes') }}</textarea>
                @error('notes') <span class="text-danger text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mt-8 flex justify-end gap-4">
                <button type="reset" class="btn btn-outline-danger">Reset</button>
                <button type="submit" class="btn btn-primary px-10">Save</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            // Initialize Select2
            $('#preferred_country').select2({
                placeholder: "Select Country",
                allowClear: true,
                width: '100%'
            });

            $('#preferred_university').select2({
                placeholder: "Select University",
                allowClear: true,
                width: '100%'
            });

            $('#preferred_course').select2({
                placeholder: "Select Course",
                allowClear: true,
                width: '100%'
            });

            // Country change event
            $('#preferred_country').on('change', function () {
                const countryId = $(this).val();

                $('#preferred_university').html('<option value="">Select University</option>').trigger('change.select2');
                $('#preferred_course').html('<option value="">Select Course</option>').trigger('change.select2');

                if (countryId) {
                    $('#preferred_university').html('<option value="">Loading...</option>').trigger('change.select2');
                    $.ajax({
                        url: "{{ route('admin.marketing.leads.get-universities') }}",
                        type: "GET",
                        data: { country_id: countryId },
                        dataType: "json",
                        success: function (data) {
                            let options = '<option value="">Select University</option>';
                            $.each(data, function (index, university) {
                                options += `<option value="${university.id}">${university.name}</option>`;
                            });
                            $('#preferred_university').html(options).trigger('change.select2');
                        },
                        error: function () {
                            $('#preferred_university').html('<option value="">Select University</option>').trigger('change.select2');
                        }
                    });
                }
            });

            // University change event
            $('#preferred_university').on('change', function () {
                const universityId = $(this).val();

                $('#preferred_course').html('<option value="">Select Course</option>').trigger('change.select2');

                if (universityId) {
                    $('#preferred_course').html('<option value="">Loading...</option>').trigger('change.select2');
                    $.ajax({
                        url: "{{ route('admin.marketing.leads.get-courses') }}",
                        type: "GET",
                        data: { university_id: universityId },
                        dataType: "json",
                        success: function (data) {
                            let options = '<option value="">Select Course</option>';
                            $.each(data, function (index, course) {
                                options += `<option value="${course.id}">${course.name}</option>`;
                            });
                            $('#preferred_course').html(options).trigger('change.select2');
                        },
                        error: function () {
                            $('#preferred_course').html('<option value="">Select Course</option>').trigger('change.select2');
                        }
                    });
                }
            });

            // Handle old values if validation failed
            @if(old('preferred_country'))
                const oldCountryId = "{{ old('preferred_country') }}";
                const oldUniversityId = "{{ old('preferred_university') }}";
                const oldCourseId = "{{ old('preferred_course') }}";

                if (oldCountryId) {
                    $.ajax({
                        url: "{{ route('admin.marketing.leads.get-universities') }}",
                        type: "GET",
                        data: { country_id: oldCountryId },
                        dataType: "json",
                        success: function (data) {
                            let options = '<option value="">Select University</option>';
                            $.each(data, function (index, university) {
                                const selected = (oldUniversityId && oldUniversityId == university.id) ? 'selected' : '';
                                options += `<option value="${university.id}" ${selected}>${university.name}</option>`;
                            });
                            $('#preferred_university').html(options).trigger('change.select2');

                            if (oldUniversityId) {
                                $.ajax({
                                    url: "{{ route('admin.marketing.leads.get-courses') }}",
                                    type: "GET",
                                    data: { university_id: oldUniversityId },
                                    dataType: "json",
                                    success: function (coursesData) {
                                        let courseOptions = '<option value="">Select Course</option>';
                                        $.each(coursesData, function (index, course) {
                                            const selected = (oldCourseId && oldCourseId == course.id) ? 'selected' : '';
                                            courseOptions += `<option value="${course.id}" ${selected}>${course.name}</option>`;
                                        });
                                        $('#preferred_course').html(courseOptions).trigger('change.select2');
                                    }
                                });
                            }
                        }
                    });
                }
            @endif
        });
    </script>
@endpush
