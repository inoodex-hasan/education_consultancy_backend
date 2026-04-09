@extends('admin.layouts.master')

@section('title', 'Edit Course')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Edit Course</h2>
        <a href="{{ route('admin.courses.index') }}" class="btn btn-secondary gap-2">Back to List</a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.courses.update', $course->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                <div class="form-group">
                    <label>University <span class="text-danger">*</span></label>
                    <select name="university_id" class="form-select" required>
                        <option value="">Select University</option>
                        @foreach ($universities as $university)
                            <option value="{{ $university->id }}"
                                {{ old('university_id', $course->university_id) == $university->id ? 'selected' : '' }}>
                                {{ $university->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-input" value="{{ old('name', $course->name) }}"
                        required>
                </div>

                <div class="form-group">
                    <label>Degree Level</label>
                    <input type="text" name="degree_level" class="form-input"
                        value="{{ old('degree_level', $course->degree_level) }}">
                </div>

                <div class="form-group">
                    <label>Duration</label>
                    <input type="text" name="duration" class="form-input"
                        value="{{ old('duration', $course->duration) }}">
                </div>

                <div class="form-group">
                    <label>Tuition Fee <span class="text-danger">*</span></label>
                    <input type="number" name="tuition_fee" class="form-input" value="{{ old('tuition_fee', $course->tuition_fee) }}" required>
                </div>

                <div class="form-group">
                    <label>Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select">
                        <option value="1" {{ $course->status ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ !$course->status ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

            </div>

            <div class="mt-8 flex justify-end gap-4">
                <button type="button" class="btn btn-outline-danger"
                    onclick="window.location.href='{{ route('admin.courses.index') }}'">Cancel</button>
                <button type="submit" class="btn btn-primary px-10">Update Course</button>
            </div>
        </form>
    </div>
@endsection
