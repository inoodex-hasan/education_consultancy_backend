<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Models\{Country, Course, CourseIntake, Student, University, User};

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:*consultant|*application')->only(['index', 'show']);
    }

    public function index(Request $request)
    {
        $this->authorize('*consultant|*application');

        $query = Student::query()->with(['marketingAssignee', 'consultantAssignee', 'applicationAssignee', 'creator']);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($stage = $request->get('stage')) {
            $query->where('current_stage', $stage);
        }

        if ($status = $request->get('status')) {
            $query->where('current_status', $status);
        }

        $students = $query->latest()->paginate(15)->withQueryString();

        return view('admin.students.index', compact('students'));
    }

    public function create(Request $request)
    {
        $this->authorize('*consultant');

        $users = User::whereHas('roles', function ($q) {
            $q->where('name', 'marketing');
        })->orderBy('name')->get(['id', 'name']);
        $countries = Country::orderBy('name')->get(['id', 'name']);
        $universities = University::where('status', '1')->orderBy('name')->get(['id', 'name']);
        
        // Auto-assign marketing from lead's created_by if lead_id is provided
        $assignedMarketingId = null;
        if ($request->has('lead_id')) {
            $lead = Lead::find($request->lead_id);
            if ($lead && $lead->created_by) {
                $assignedMarketingId = $lead->created_by;
            }
        }

        return view('admin.students.create', compact('users', 'countries', 'universities', 'assignedMarketingId'));
    }

    public function store(Request $request)
    {
        $this->authorize('*consultant');

        $validated = $this->validateStudent($request);
        $validated['created_by'] = auth()->id();

        if ($request->hasFile('documents')) {
            $documents = [];
            foreach ($request->file('documents') as $file) {
                $path = $file->store('documents/students', 'public');
                $documents[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                ];
            }
            $validated['documents'] = $documents;
        }

        Student::create($validated);

        return redirect()
            ->route('admin.students.index')
            ->with('success', 'Student created successfully.');
    }

    public function show(Student $student)
    {
        $this->authorize('*consultant|*application');

        $student->load(['marketingAssignee', 'consultantAssignee', 'applicationAssignee', 'creator', 'country', 'university', 'course', 'intake', 'applications.university', 'applications.course', 'applications.intake']);

        return view('admin.students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        $this->authorize('*consultant');

        $users = User::whereHas('roles', function ($q) {
            $q->where('name', 'marketing');
        })->orderBy('name')->get(['id', 'name']);
        $countries = Country::orderBy('name')->get(['id', 'name']);
        $universities = $student->country_id ? University::where('country_id', $student->country_id)->where('status', '1')->orderBy('name')->get(['id', 'name']) : [];
        $courses = $student->university_id ? Course::where('university_id', $student->university_id)->get(['id', 'name']) : [];
        $intakes = $student->course_id ? CourseIntake::where('course_id', $student->course_id)->get(['id', 'intake_name']) : [];

        return view('admin.students.edit', compact('student', 'users', 'countries', 'universities', 'courses', 'intakes'));
    }

    public function update(Request $request, Student $student)
    {
        $this->authorize('*consultant');

        $validated = $this->validateStudent($request);

        $documents = $student->documents ?? [];

        // Handle deletions
        if ($request->has('delete_documents')) {
            foreach ($request->delete_documents as $index) {
                if (isset($documents[$index])) {
                    Storage::disk('public')->delete($documents[$index]['path']);
                    unset($documents[$index]);
                }
            }
            $documents = array_values($documents); // Re-index array
        }

        // Handle new uploads
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                $path = $file->store('documents/students', 'public');
                $documents[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                ];
            }
        }

        $validated['documents'] = $documents;

        $student->update($validated);

        return redirect()
            ->route('admin.students.index')
            ->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        $this->authorize('*consultant');

        $student->delete();

        return redirect()
            ->route('admin.students.index')
            ->with('success', 'Student deleted successfully.');
    }

    private function validateStudent(Request $request): array
    {
        return $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'father_name' => ['nullable', 'string', 'max:255'],
            'passport_number' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'dob' => ['nullable', 'date'],
            'country_id' => ['nullable', 'exists:countries,id'],
            // 'current_stage' => [
            //     'required',
            //     Rule::in(['lead', 'counseling', 'payment', 'application', 'offer', 'visa', 'enrolled']),
            // ],
            // 'current_status' => [
            //     'required',
            //     Rule::in(['pending', 'applied', 'rejected', 'withdrawn', 'visa_processing', 'enrolled']),
            // ],
            'assigned_marketing_id' => ['nullable', 'exists:users,id'],
            // 'assigned_consultant_id' => ['nullable', 'exists:users,id'],
            // 'assigned_application_id' => ['nullable', 'exists:users,id'],
            'mothers_name' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'ssc_result' => ['nullable', 'string', 'max:255'],
            'hsc_result' => ['nullable', 'string', 'max:255'],
            'ielts_score' => ['nullable', 'string', 'max:255'],
            'subject' => ['nullable', 'string', 'max:255'],
            'university_id' => ['nullable', 'exists:universities,id'],
            'course_id' => ['nullable', 'exists:courses,id'],
            'course_intake_id' => ['nullable', 'exists:course_intakes,id'],
            'documents' => ['nullable', 'array'],
            'documents.*' => ['nullable', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:5120'], // 5MB limit
        ]);
    }
}
