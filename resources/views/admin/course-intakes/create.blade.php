@extends('admin.layouts.master')

@section('title', 'Create Course Intake')

@section('content')

    <div class="mb-5">
        <h2 class="text-xl font-semibold">Add Course Intake</h2>
    </div>

    <div class="panel">
        <form action="{{ route('admin.course-intakes.store') }}" method="POST">
            @include('admin.course-intakes._form')
        </form>
    </div>

@endsection