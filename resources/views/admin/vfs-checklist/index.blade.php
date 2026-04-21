@extends('admin.layouts.master')

@section('title', 'VFS Checklist')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">VFS Checklist</h2>
    </div>

    <div class="panel mt-6">
        <!-- Search -->
        <form action="{{ route('admin.vfs-checklist.index') }}" method="GET" class="mb-6">
            <div class="flex gap-3">
                <input type="text" name="search" placeholder="Search by Application ID or Student Name..."
                    value="{{ request('search') }}" class="form-input flex-1" />
                <button type="submit" class="btn btn-primary">Search</button>
                @if(request('search'))
                    <a href="{{ route('admin.vfs-checklist.index') }}" class="btn btn-outline-danger">Clear</a>
                @endif
            </div>
        </form>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table-hover">
                <thead>
                    <tr>
                        <th>Application ID</th>
                        <th>Student Name</th>
                        <th>University</th>
                        <th class="text-center">Checked Items</th>
                        <th class="text-center">Total Items</th>
                        <th class="text-center">Progress</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $activeTemplateNames = \App\Models\VfsChecklistTemplate::where('is_active', true)->pluck('item_name')->toArray();
                    @endphp
                    @forelse($applications as $app)
                        @php
                            $activeItems = $app->vfsChecklist->whereIn('checklist_item', $activeTemplateNames);
                            $totalItems = $activeItems->count();
                            $checkedItems = $activeItems->where('is_checked', true)->count();
                            $progress = $totalItems > 0 ? round(($checkedItems / $totalItems) * 100, 0) : 0;
                        @endphp
                        <tr>
                            <td class="font-bold text-primary">{{ $app->application_id }}</td>
                            <td>{{ $app->student->first_name }} {{ $app->student->last_name }}</td>
                            <td>{{ $app->university?->name ?? '-' }}</td>
                            <td class="text-center">{{ $checkedItems }}</td>
                            <td class="text-center">{{ $totalItems }}</td>
                            <td class="text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <div class="w-24 bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                                        <div class="bg-{{ $progress == 100 ? 'success' : 'primary' }} h-2.5 rounded-full"
                                            style="width: {{ $progress }}%"></div>
                                    </div>
                                    <span class="text-xs font-bold">{{ $progress }}%</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.vfs-checklist.show', $app) }}" class="btn btn-sm btn-primary">
                                    View Checklist
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-gray-400 py-12">
                                @if(request('search'))
                                    No applications found matching "{{ request('search') }}".
                                @else
                                    No applications found.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $applications->links() }}
        </div>
    </div>
@endsection