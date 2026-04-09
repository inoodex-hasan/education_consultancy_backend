@extends('admin.layouts.master')

@section('title', 'Lead Details - ' . $lead->student_name)

@section('content')
    <div>
        <div class="flex items-center justify-between flex-wrap gap-4 mb-6">
            <h2 class="text-xl font-semibold uppercase">Lead Details</h2>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.marketing.leads.index') }}" class="btn btn-outline-primary">Back to List</a>
                @can('*marketing')
                    <a href="{{ route('admin.marketing.leads.edit', $lead->id) }}" class="btn btn-primary">Edit Lead</a>
                @endcan
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Student Information -->
            <div class="lg:col-span-2 space-y-6">
                <div class="panel">
                    <div class="flex items-center justify-between mb-5">
                        <h5 class="font-semibold text-lg">Student Information</h5>
                        <span
                            class="badge @if ($lead->status == 'pending') badge-outline-warning @elseif($lead->status == 'interested') badge-outline-success @elseif($lead->status == 'forwarded') badge-outline-info @else badge-outline-danger @endif capitalize">
                            {{ $lead->status }}
                        </span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-white-dark mb-1">Full Name</label>
                            <p class="font-semibold text-gray-light">{{ $lead->student_name }}</p>
                        </div>
                        <div>
                            <label class="text-white-dark mb-1">Phone Number</label>
                            <p class="font-semibold text-gray-light">{{ $lead->phone }}</p>
                        </div>
                        <div>
                            <label class="text-white-dark mb-1">Email Address</label>
                            <p class="font-semibold text-gray-light">{{ $lead->email ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-white-dark mb-1">Current Education</label>
                            <p class="font-semibold text-gray-light">{{ $lead->current_education ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Preferences & Source -->
                <div class="panel">
                    <h5 class="font-semibold text-lg mb-5">Preferences & Source</h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-white-dark mb-1">Preferred Country</label>
                            <p class="font-semibold text-gray-light">{{ $lead->country->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-white-dark mb-1">Preferred Course</label>
                            <p class="font-semibold text-gray-light">{{ $lead->course->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-white-dark mb-1">Contact Source</label>
                            <p class="font-semibold text-gray-light">
                                <span class="badge badge-outline-primary">{{ $lead->source }}</span>
                            </p>
                        </div>
                        <div>
                            <label class="text-white-dark mb-1">Follow-up Date</label>
                            @php($followUpHistory = collect($lead->follow_up_date_history))
                            @if($followUpHistory->isNotEmpty())
                                @php($currentFollowUpDate = $followUpHistory->last())
                                <p class="font-semibold {{ $currentFollowUpDate->isPast() ? 'text-danger' : 'text-gray-light' }}">
                                    {{ $currentFollowUpDate->format('M d, Y') }}
                                </p>

                                @if($followUpHistory->count() > 1)
                                    <div class="mt-2">
                                        <label class="text-white-dark mb-2 block text-xs">Follow-up History</label>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($followUpHistory->slice(0, -1)->reverse() as $historyDate)
                                                <span class="rounded-full bg-black/5 px-2.5 py-1 text-[11px] font-medium text-white-dark dark:bg-white/[0.08]">
                                                    {{ $historyDate->format('M d, Y') }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @else
                                <p class="font-semibold text-gray-light">N/A</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Internal Notes -->
                <div class="panel">
                    <h5 class="font-semibold text-lg mb-5">Internal Notes</h5>
                    <div class="bg-black/10 p-4 rounded-lg min-h-[100px]">
                        <p class="whitespace-pre-wrap text-gray-light">{{ $lead->notes ?? 'No notes available.' }}</p>
                    </div>
                </div>
            </div>

            <!-- Meta Information -->
            <div class="space-y-6">
                <div class="panel">
                    <h5 class="font-semibold text-lg mb-5">System Tracking</h5>
                    <div class="space-y-4 text-sm">
                        <div class="flex justify-between">
                            <span class="text-white-dark">Collected By:</span>
                            <span class="font-semibold">{{ $lead->creator->name ?? 'System' }}</span>
                        </div>
                        {{-- <div class="flex justify-between">
                            <span class="text-white-dark">Assigned Consultant:</span>
                            <span class="font-semibold">{{ $lead->consultant->name ?? 'Unassigned' }}</span>
                        </div> --}}
                        <hr class="border-white-light dark:border-[#1b2e4b]">
                        <div class="flex justify-between">
                            <span class="text-white-dark">Created At:</span>
                            <span>{{ $lead->created_at->format('M d, Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-white-dark">Last Updated:</span>
                            <span>{{ $lead->updated_at->format('M d, Y H:i') }}</span>
                        </div>
                    </div>
                </div>

                @can('*marketing')
                    <div class="panel border-danger">
                        <h5 class="font-semibold text-lg mb-5 text-danger">Danger Zone</h5>
                        <p class="text-xs text-white-dark mb-4">Deleting this lead will remove all associated data permanently.
                        </p>
                        <form action="{{ route('admin.marketing.leads.destroy', $lead->id) }}" method="POST"
                            onsubmit="return confirm('Are you absolutely sure? This cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-full">Delete Lead</button>
                        </form>
                    </div>
                @endcan
            </div>
        </div>
    </div>
@endsection
