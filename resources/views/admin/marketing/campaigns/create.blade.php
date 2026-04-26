@extends('admin.layouts.master')

@section('title', 'Create Marketing Campaign')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">New Marketing Campaign</h2>
        <a href="{{ route('admin.marketing.campaigns.index') }}" class="btn btn-outline-primary gap-2">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M19 12H5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M12 19L5 12L12 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Back to List
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.marketing.campaigns.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="name">Campaign Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Enter campaign name"
                        class="form-input" required />
                    @error('name')<span class="text-danger text-xs">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label for="start_date">Start Date</label>
                    <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}"
                        class="form-input" />
                    @error('start_date')<span class="text-danger text-xs">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label for="end_date">End Date</label>
                    <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}"
                        class="form-input" />
                    @error('end_date')<span class="text-danger text-xs">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label for="boosting_status">Initial Boosting Status</label>
                    <select name="boosting_status" id="boosting_status" class="form-select">
                        <option value="off" {{ old('boosting_status') == 'off' ? 'selected' : '' }}>OFF</option>
                        <option value="on" {{ old('boosting_status') == 'on' ? 'selected' : '' }}>ON</option>
                    </select>
                    @error('boosting_status')<span class="text-danger text-xs">{{ $message }}</span>@enderror
                </div>
                <div class="md:col-span-2">
                    <label for="notes">Notes</label>
                    <textarea name="notes" id="notes" rows="3" class="form-textarea" placeholder="Describe the campaign target audience, budget, or platforms...">{{ old('notes') }}</textarea>
                    @error('notes')<span class="text-danger text-xs">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="mt-8 flex items-center justify-end gap-4">
                <button type="submit" class="btn btn-primary px-8">Create Campaign</button>
            </div>
        </form>
    </div>
@endsection
