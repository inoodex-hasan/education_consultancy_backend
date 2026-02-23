<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseIntake;
use Illuminate\Http\Request;

class CourseIntakeController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:*editor');
    }
    public function index(Request $request)
    {
        $this->authorize('*editor');

        $query = CourseIntake::with('course.university');

        if ($search = $request->get('search')) {
            $query->where('intake_name', 'like', "%{$search}%");
        }

        if ($courseId = $request->get('course_id')) {
            $query->where('course_id', $courseId);
        }

        $intakes = $query->latest()->paginate(15)->withQueryString();
        $courses = Course::where('status', 1)->orderBy('name')->get();

        return view('admin.course-intakes.index', compact('intakes', 'courses'));
    }

    public function create()
    {
        $this->authorize('*editor');

        $courses = Course::where('status', 1)->orderBy('name')->get();

        return view('admin.course-intakes.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $this->authorize('*editor');

        $validated = $this->validateIntake($request);

        CourseIntake::create($validated);

        return redirect()
            ->route('admin.course-intakes.index')
            ->with('success', 'Intake created successfully.');
    }

    public function edit(CourseIntake $courseIntake)
    {
        $this->authorize('*editor');

        $courses = Course::where('status', 1)->orderBy('name')->get();

        return view('admin.course-intakes.edit', compact('courseIntake', 'courses'));
    }

    public function update(Request $request, CourseIntake $courseIntake)
    {
        $this->authorize('*editor');

        $validated = $this->validateIntake($request);

        $courseIntake->update($validated);

        return redirect()
            ->route('admin.course-intakes.index')
            ->with('success', 'Intake updated successfully.');
    }

    public function destroy(CourseIntake $courseIntake)
    {
        $this->authorize('*editor');

        $courseIntake->delete();

        return redirect()
            ->route('admin.course-intakes.index')
            ->with('success', 'Intake deleted successfully.');
    }

    private function validateIntake(Request $request): array
    {
        return $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'intake_name' => ['required', 'string', 'max:255'],
            'application_start_date' => ['nullable', 'date'],
            'application_deadline' => ['nullable', 'date', 'after_or_equal:application_start_date'],
            'class_start_date' => ['nullable', 'date'],
            'status' => ['required', 'boolean'],
        ]);
    }
}
