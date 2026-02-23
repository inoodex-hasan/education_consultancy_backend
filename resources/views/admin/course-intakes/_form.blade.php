@csrf
<div class="grid grid-cols-1 md:grid-cols-2 gap-5">

    <div>
        <label class="form-label">Course</label>
        <select name="course_id" class="form-select" required>
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
            <option value="1" {{ old('status', $courseIntake->status ?? 1) == 1 ? 'selected' : '' }}>Active</option>
            <option value="0" {{ old('status', $courseIntake->status ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>
</div>

<div class="mt-5">
    <div class="mt-8 flex justify-end gap-4">
        <button type="reset" class="btn btn-outline-danger">Reset</button>
        <button type="submit" class="btn btn-primary px-10">Save Course Intake</button>
    </div>
</div>