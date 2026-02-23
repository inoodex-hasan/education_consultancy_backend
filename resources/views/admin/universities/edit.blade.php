@extends('admin.layouts.master')

@section('title', 'Edit University')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Edit University</h2>
        <a href="{{ route('admin.universities.index') }}" class="btn btn-secondary gap-2">Back to List</a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.universities.update', $university->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                <div class="form-group">
                    <label>Country <span class="text-danger">*</span></label>
                    <select name="country_id" class="form-select" required>
                        <option value="">Select Country</option>
                        @foreach ($countries as $country)
                            <option value="{{ $country->id }}" {{ old('country_id', $university->country_id) == $country->id ? 'selected' : '' }}>
                                {{ $country->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-input" value="{{ old('name', $university->name) }}" required>
                </div>

                <div class="form-group">
                    <label>Short Name (Optional)</label>
                    <input type="text" name="short_name" class="form-input"
                        value="{{ old('short_name', $university->short_name) }}">
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-input" value="{{ old('email', $university->email) }}">
                </div>

                <div class="form-group">
                    <label>Website</label>
                    <input type="url" name="website" class="form-input" value="{{ old('website', $university->website) }}">
                </div>

                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" name="phone" class="form-input" value="{{ old('phone', $university->phone) }}">
                </div>

                <div class="form-group">
                    <label>Address</label>
                    <textarea name="address" class="form-input">{{ old('address', $university->address) }}</textarea>
                </div>

                <div class="form-group">
                    <label>Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select">
                        <option value="1" {{ $university->status ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ !$university->status ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

            </div>

            <div class="mt-8 flex justify-end gap-4">
                <button type="reset" class="btn btn-outline-danger">Reset</button>
                <button type="submit" class="btn btn-primary px-10">Update</button>
            </div>
        </form>
    </div>
@endsection