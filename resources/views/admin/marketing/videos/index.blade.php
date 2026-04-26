@extends('admin.layouts.master')

@section('title', 'Marketing Videos')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Marketing Videos</h2>
    </div>

    <!-- Add New Video -->
    <div class="panel mt-6">
        <h3 class="text-lg font-bold mb-4">Add New Video</h3>
        <form action="{{ route('admin.marketing.videos.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @csrf
            <div>
                <input type="text" name="video_name" placeholder="Video name" class="form-input" required />
            </div>
            <div>
                <select name="status" class="form-select">
                    <option value="pending">Pending</option>
                    <option value="not_edited">Not Edited</option>
                    <option value="editing">Editing</option>
                    <option value="ready">Ready</option>
                    <option value="uploaded">Uploaded</option>
                </select>
            </div>
            <div>
                <button type="submit" class="btn btn-primary">Add Video</button>
            </div>
        </form>
    </div>

    <!-- Filter -->
    <div class="panel mt-6">
        <form action="{{ route('admin.marketing.videos.index') }}" method="GET" class="flex flex-wrap gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search videos..." class="form-input w-full md:w-auto" />
            <select name="status" class="form-select w-full md:w-auto">
                <option value="">Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="not_edited" {{ request('status') == 'not_edited' ? 'selected' : '' }}>Not Edited</option>
                <option value="editing" {{ request('status') == 'editing' ? 'selected' : '' }}>Editing</option>
                <option value="ready" {{ request('status') == 'ready' ? 'selected' : '' }}>Ready</option>
                <option value="uploaded" {{ request('status') == 'uploaded' ? 'selected' : '' }}>Uploaded</option>
            </select>
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('admin.marketing.videos.index') }}" class="btn btn-outline-primary">Reset</a>
        </form>
    </div>

    <!-- Videos Table -->
    <div class="panel mt-6">
        <div class="datatable">
            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto">
                    <thead>
                        <tr>
                            <th>Video Name</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($videos as $video)
                            <tr>
                                <td>{{ $video->video_name }}</td>
                                <td>
                                    @php
                                        $vClass = [
                                            'pending' => 'badge-outline-dark',
                                            'not_edited' => 'badge-outline-danger',
                                            'editing' => 'badge-outline-warning',
                                            'ready' => 'badge-outline-success',
                                            'uploaded' => 'badge-outline-primary',
                                        ][$video->status] ?? 'badge-outline-dark';
                                    @endphp
                                    <span class="badge {{ $vClass }} capitalize text-[10px]">{{ str_replace('_', ' ', $video->status) }}</span>
                                </td>
                                <td>{{ $video->created_at->format('M d, Y') }}</td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <!-- Edit Modal Trigger -->
                                        <button type="button" class="text-primary hover:text-primary-dark" onclick="document.getElementById('edit-modal-{{ $video->id }}').classList.remove('hidden')">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                            </svg>
                                        </button>
                                        <!-- Delete Form -->
                                        <form action="{{ route('admin.marketing.videos.destroy', $video) }}" method="POST" onsubmit="return confirm('Delete this video?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-danger hover:text-red-700">
                                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M3 6h18m-2 0v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6m3 0V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div id="edit-modal-{{ $video->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
                                <div class="panel w-full max-w-md">
                                    <h3 class="text-lg font-bold mb-4">Edit Video</h3>
                                    <form action="{{ route('admin.marketing.videos.update', $video) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="space-y-4">
                                            <div>
                                                <label class="text-sm font-bold">Video Name</label>
                                                <input type="text" name="video_name" value="{{ $video->video_name }}" class="form-input" required />
                                            </div>
                                            <div>
                                                <label class="text-sm font-bold">Status</label>
                                                <select name="status" class="form-select">
                                                    <option value="pending" {{ $video->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                    <option value="not_edited" {{ $video->status == 'not_edited' ? 'selected' : '' }}>Not Edited</option>
                                                    <option value="editing" {{ $video->status == 'editing' ? 'selected' : '' }}>Editing</option>
                                                    <option value="ready" {{ $video->status == 'ready' ? 'selected' : '' }}>Ready</option>
                                                    <option value="uploaded" {{ $video->status == 'uploaded' ? 'selected' : '' }}>Uploaded</option>
                                                </select>
                                            </div>
                                            <div class="flex gap-2">
                                                <button type="submit" class="btn btn-primary">Update</button>
                                                <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('edit-modal-{{ $video->id }}').classList.add('hidden')">Cancel</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-10 text-white-dark">No videos found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $videos->links() }}
            </div>
        </div>
    </div>
@endsection
