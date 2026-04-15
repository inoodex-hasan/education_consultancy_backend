@extends('admin.layouts.master')

@section('title', 'Create Course Intake')

@section('content')

    <div class="mb-5">
        <h2 class="text-xl font-semibold">Add Course Intake</h2>
    </div>

    <div class="panel">
        <form action="{{ route('admin.course-intakes.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <div>
                    <label class="form-label">Course</label>
                    <select name="course_id" class="form-select select2" required>
                        <option value="">Select Course</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ old('course_id', $courseIntake->course_id ?? '') == $course->id ? 'selected' : '' }}>
                                {{ $course->university->name ?? '' }} - {{ $course->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="form-label">Intake Name<span class="text-danger">*</span></label>
                    <input type="text" name="intake_name" class="form-input"
                        value="{{ old('intake_name', $courseIntake->intake_name ?? '') }}" required>
                </div>

                <div>
                    <label class="form-label">Application Start Date<span class="text-danger">*</span></label>
                    <input type="date" name="application_start_date" class="form-input"
                        value="{{ old('application_start_date', $courseIntake->application_start_date ?? '') }}">
                </div>

                <div>
                    <label class="form-label">Application Deadline<span class="text-danger">*</span></label>
                    <input type="date" name="application_deadline" class="form-input"
                        value="{{ old('application_deadline', $courseIntake->application_deadline ?? '') }}">
                </div>

                <div>
                    <label class="form-label">Class Start Date (Optional)</label>
                    <input type="date" name="class_start_date" class="form-input"
                        value="{{ old('class_start_date', $courseIntake->class_start_date ?? '') }}">
                </div>

                <div>
                    <label class="form-label">Status<span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        <option value="1" {{ old('status', $courseIntake->status ?? 1) == 1 ? 'selected' : '' }}>Active
                        </option>
                        <option value="0" {{ old('status', $courseIntake->status ?? 1) == 0 ? 'selected' : '' }}>Inactive
                        </option>
                    </select>
                </div>
            </div>

            <div class="mt-5">
                <div class="mt-8 flex justify-end gap-4">
                    <button type="reset" class="btn btn-outline-danger">Reset</button>
                    <button type="submit" class="btn btn-primary px-10">Save Course Intake</button>
                </div>
            </div>
        </form>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('.select2').select2({
                placeholder: "Select Course",
                allowClear: true,
                width: '100%'
            });
        });
    </script>
@endpush

@push('styles')
    <style>
        /* Match your input style */
        .select2-container .select2-selection--single {
            height: 42px !important;
            border: 1px solid #e0e6ed !important;
            border-radius: 6px !important;
            padding: 6px 10px !important;
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

        /* Dropdown */
        .select2-dropdown {
            border-radius: 6px !important;
            border: 1px solid #e0e6ed !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        /* Search input */
        .select2-search__field {
            padding: 6px !important;
            border-radius: 4px !important;
            border: 1px solid #e0e6ed !important;
        }

        /* Highlight option */
        .select2-results__option--highlighted {
            background-color: #4361ee !important;
            color: #fff !important;
        }

        /* Selected option */
        .select2-results__option--selected {
            background-color: #e0e6ed !important;
            color: #0e1726 !important;
        }
    </style>
@endpush