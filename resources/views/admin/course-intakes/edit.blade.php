@extends('admin.layouts.master')

@section('title', 'Edit Course Intake')

@section('content')

    <div class="mb-5">
        <h2 class="text-xl font-semibold">Edit Course Intake</h2>
    </div>

    <div class="panel">
        <form action="{{ route('admin.course-intakes.update', $courseIntake) }}" method="POST">
            @method('PUT')
            @include('admin.course-intakes._form')
        </form>
    </div>

@endsection