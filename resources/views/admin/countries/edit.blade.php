@extends('admin.layouts.master')

@section('title', 'Edit Country')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Edit Country</h2>
        <a href="{{ route('admin.countries.index') }}" class="btn btn-secondary gap-2">
            Back to List
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.countries.update', $country->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                <div class="form-group">
                    <label>Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-input" value="{{ old('name', $country->name) }}" required>
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-select">
                        <option value="1" {{ $country->status ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ !$country->status ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

            </div>

            <div class="mt-8 flex justify-end gap-4">
                <button type="button" class="btn btn-outline-danger"
                    onclick="window.location.href='{{ route('admin.countries.index') }}'">Cancel</button>
                <button type="submit" class="btn btn-primary px-10">Update Country</button>
            </div>
        </form>
    </div>
@endsection