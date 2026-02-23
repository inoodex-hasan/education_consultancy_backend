@extends('admin.layouts.master')

@section('title', 'Course Intakes')

@section('content')

    <div class="mb-5 flex items-center justify-between">
        <h2 class="text-xl font-semibold">Course Intakes</h2>
        <a href="{{ route('admin.course-intakes.create') }}" class="btn btn-primary">
            Add Intake
        </a>
    </div>

    <div class="panel mt-6">
        <div class="mb-5 flex flex-col gap-5 md:flex-row md:items-center">
            <form action="{{ route('admin.course-intakes.index') }}" method="GET"
                class="flex flex-1 flex-col gap-5 md:flex-row md:items-center w-full">
                <div class="relative w-full md:w-80">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search intake name..."
                        class="form-input ltr:pr-11 rtl:pl-11" />
                    <button type="submit"
                        class="absolute inset-y-0 flex items-center hover:text-primary ltr:right-4 rtl:left-4">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <circle cx="11.5" cy="11.5" r="9.5" stroke="currentColor" stroke-width="1.5"
                                opacity="0.5" />
                            <path d="M18.5 18.5L22 22" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" />
                        </svg>
                    </button>
                </div>
                <div class="flex gap-2">
                    <select name="course_id" class="form-select w-full md:w-80 pr-10">
                        <option value="">All Courses</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                {{ $course->name }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('admin.course-intakes.index') }}" class="btn btn-outline-danger">Reset</a>
                </div>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>University</th>
                        <th>Course</th>
                        <th>Intake</th>
                        <th>Application Start</th>
                        <th>Deadline</th>
                        <th>Class Start</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($intakes as $intake)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $intake->course->university->name ?? 'N/A' }}</td>
                            <td>{{ $intake->course->name ?? 'N/A' }}</td>
                            <td>{{ $intake->intake_name ?? 'N/A' }}</td>
                            <td>{{ $intake->application_start_date?->format('d M Y') ?? 'N/A' }}</td>
                            <td>{{ $intake->application_deadline?->format('d M Y') ?? 'N/A' }}</td>
                            <td>{{ $intake->class_start_date?->format('d M Y') ?? 'N/A' }}</td>
                            <td>
                                <span class="badge {{ $intake->status ? 'bg-success' : 'bg-danger' }}">
                                    {{ $intake->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.course-intakes.edit', $intake->id) }}"
                                    class="btn btn-sm btn-outline-primary">Edit</a>

                                <form action="{{ route('admin.course-intakes.destroy', $intake->id) }}" method="POST"
                                    class="inline-block" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">No intakes found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $intakes->links() }}
        </div>
    </div>

@endsection