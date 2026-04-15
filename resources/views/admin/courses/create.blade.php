@extends('admin.layouts.master')

@section('title', 'Create Course')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Create Course</h2>
        <a href="{{ route('admin.courses.index') }}" class="btn btn-secondary gap-2">Back to List</a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.courses.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                <div class="form-group">
                    <label>University <span class="text-danger">*</span></label>
                    <select name="university_id" class="form-select select2" required>
                        <option></option>
                        @foreach ($universities as $university)
                            <option value="{{ $university->id }}" {{ old('university_id') == $university->id ? 'selected' : '' }}>
                                {{ $university->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('university_id')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-input" value="{{ old('name') }}" required>
                </div>

                <div class="form-group">
                    <label>Degree Level</label>
                    <input type="text" name="degree_level" class="form-input" value="{{ old('degree_level') }}">
                </div>

                <div class="form-group">
                    <label>Duration (Month)</label>
                    <input type="text" name="duration" class="form-input" value="{{ old('duration') }}">
                </div>

                <!-- <div class="form-group">
                                                                                                    <label>Tuition Fee <span class="text-danger">*</span></label>
                                                                                                    <input type="number" name="tuition_fee" class="form-input"
                                                                                                        value="{{ old('tuition_fee') }}" required>
                                                                                                </div> -->

                <div class="form-group">
                    <label>Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('status', 1) == 0 ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

            </div>

            <div class="mt-8 flex justify-end gap-4">
                <button type="reset" class="btn btn-outline-danger">Reset</button>
                <button type="submit" class="btn btn-primary px-10">Save Course</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('.select2').select2({
                placeholder: "Select University",
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