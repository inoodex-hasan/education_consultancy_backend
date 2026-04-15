@extends('admin.layouts.master')

@section('title', 'Create University')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Create University</h2>
        <a href="{{ route('admin.universities.index') }}" class="btn btn-secondary gap-2">Back to List</a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.universities.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                <div class="form-group">
                    <label>Country <span class="text-danger">*</span></label>
                    <select name="country_id" class="form-select" required>
                        <option value="">Select Country</option>
                        @foreach ($countries as $country)
                            <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>
                                {{ $country->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('country_id')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-input" value="{{ old('name') }}" required>
                    @error('name')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Short Name (Optional)</label>
                    <input type="text" name="short_name" class="form-input" value="{{ old('short_name') }}">
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-input" value="{{ old('email') }}">
                </div>

                <div class="form-group">
                    <label>Website</label>
                    <input type="url" name="website" class="form-input" value="{{ old('website') }}">
                </div>

                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" name="phone" class="form-input" value="{{ old('phone') }}">
                </div>

                <div class="form-group">
                    <label>Address</label>
                    <textarea name="address" class="form-input">{{ old('address') }}</textarea>
                </div>

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
                <button type="submit" class="btn btn-primary px-10">Save University</button>
            </div>
        </form>
    </div>
@endsection