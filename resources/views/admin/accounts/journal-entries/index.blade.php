@extends('admin.layouts.master')

@section('title', 'Journal Entries')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Journal Ledger</h2>
        <a href="{{ route('admin.journal-entries.create') }}" class="btn btn-primary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Post New Voucher
        </a>
    </div>

    {{-- Filter Section --}}
    <div class="panel mt-4">
        <form method="GET" action="{{ route('admin.journal-entries.index') }}">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                {{-- Start Date --}}
                <div>
                    <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1 block">Start Date</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                        class="form-input w-full text-sm">
                </div>

                {{-- End Date --}}
                <div>
                    <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1 block">End Date</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                        class="form-input w-full text-sm">
                </div>

                {{-- Reference Number --}}
                <div>
                    <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1 block">Reference No</label>
                    <input type="text" name="reference_number" value="{{ request('reference_number') }}"
                        placeholder="Search reference..." class="form-input w-full text-sm">
                </div>

                {{-- Period --}}
                <div>
                    <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1 block">Period</label>
                    <select name="period_id" class="form-select w-full text-sm">
                        <option value="">All Periods</option>
                        @foreach($periods as $period)
                            <option value="{{ $period->id }}" {{ request('period_id') == $period->id ? 'selected' : '' }}>
                                {{ $period->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Student Name --}}
                <div>
                    <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1 block">Student Name</label>
                    <input type="text" name="student_name" value="{{ request('student_name') }}"
                        placeholder="Search student..." class="form-input w-full text-sm">
                </div>

                {{-- Status --}}
                <div>
                    <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1 block">Status</label>
                    <select name="status" class="form-select w-full text-sm">
                        <option value="">All Status</option>
                        <option value="posted" {{ request('status') == 'posted' ? 'selected' : '' }}>Posted</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="reversed" {{ request('status') == 'reversed' ? 'selected' : '' }}>Reversed</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end gap-2 mt-4">
                <a href="{{ route('admin.journal-entries.index') }}" class="btn btn-outline-secondary btn-sm">
                    Reset
                </a>
                <button type="submit" class="btn btn-primary btn-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    Filter
                </button>
            </div>
        </form>
    </div>

    <div class="panel mt-4">
        <div class="table-responsive">
            <table class="table-hover w-full table-auto">
                <thead>
                    <tr>
                        <th>Transaction Date</th>
                        <th>Reference</th>
                        <th>Student</th>
                        <th>Period</th>
                        <th>Voucher Amount</th>
                        <th>Posted By</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($entries as $entry)
                        <tr>
                            <td class="whitespace-nowrap text-xs font-semibold">{{ $entry->date->format('M d, Y') }}</td>
                            <td class="font-bold underline text-primary">
                                <a
                                    href="{{ route('admin.journal-entries.show', $entry) }}">{{ $entry->reference_number }}</a>
                            </td>
                            <td class="font-xs">
                                @if ($entry->application)
                                    <div class="flex flex-col">
                                        <span
                                            class="font-bold text-black dark:text-white">{{ $entry->application->student->first_name }}
                                            {{ $entry->application->student->last_name }}</span>
                                        <span
                                            class="text-[10px] text-gray-500 uppercase">{{ $entry->application->application_id }}</span>
                                    </div>
                                @else
                                    <span class="text-gray-400 italic text-[11px]">General Entry</span>
                                @endif
                            </td>
                            <td>
                                <span
                                    class="badge badge-outline-secondary text-[10px] uppercase">{{ $entry->period->name }}</span>
                            </td>
                            <td class="font-bold">{{ number_format($entry->total_amount, 2) }}</td>
                            <td class="text-xs">{{ $entry->creator->name }}</td>
                            <td class="text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.journal-entries.show', $entry) }}"
                                        class="btn btn-sm btn-outline-primary">View</a>
                                    <form action="{{ route('admin.journal-entries.destroy', $entry) }}" method="POST"
                                        class="inline-block" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-gray-400 py-16">
                                <div class="flex flex-col items-center">
                                    <svg width="60" height="60" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="1" opacity="0.2">
                                        <path
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="mt-2">No journal vouchers recorded yet.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $entries->links() }}
        </div>
    </div>
@endsection
