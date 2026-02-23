@extends('admin.layouts.master')

@section('title', 'Commissions')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Commissions</h2>
    </div>

    <div class="panel mt-6">
        <div class="mb-5 flex flex-col gap-5 md:flex-row md:items-center">
            <form action="{{ route('admin.commissions.index') }}" method="GET"
                class="flex flex-1 flex-col gap-5 md:flex-row md:items-center w-full">
                <div class="relative w-full md:w-[405px]">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search student, user or role..." class="form-input ltr:pr-11 rtl:pl-11" />
                    <button type="submit"
                        class="absolute inset-y-0 flex items-center hover:text-primary ltr:right-4 rtl:left-4">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <circle cx="11.5" cy="11.5" r="9.5" stroke="currentColor" stroke-width="1.5"
                                opacity="0.5" />
                            <path d="M18.5 18.5L22 22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                    </button>
                </div>
                <button type="submit" class="btn btn-primary">Filter</button>
            </form>
        </div>

        <div class="datatable">
            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Student</th>
                            <th>Recipient (User)</th>
                            <th>Role</th>
                            <th>Payment Amount</th>
                            <th>Commission (%)</th>
                            <th>Commission Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($commissions->count() > 0)
                            @foreach ($commissions as $commission)
                                <tr>
                                    <td>{{ $commission->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="font-semibold text-primary">
                                            {{ $commission->payment->student->first_name ?? 'N/A' }}
                                            {{ $commission->payment->student->last_name ?? '' }}
                                        </div>
                                        <div class="text-xs text-white-dark">
                                            {{ $commission->payment->application->application_id ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="font-semibold">{{ $commission->user->name ?? 'N/A' }}</div>
                                        <div class="text-xs text-white-dark">{{ $commission->user->email ?? '' }}</div>
                                    </td>
                                    <td class="capitalize">{{ $commission->role }}</td>
                                    <td>
                                        @php
                                            $totalApplicationAmount = \App\Models\Payment::where(
                                                'application_id',
                                                $commission->payment->application_id,
                                            )
                                                ->where('payment_status', 'pending')
                                                ->orWhere('payment_status', 'completed')
                                                ->sum('amount');
                                        @endphp
                                        {{ get_setting('currency_symbol', 'BDT') }}
                                        {{ number_format($totalApplicationAmount, 2) }} Tk
                                    </td>
                                    <td>{{ number_format($commission->percentage, 2) }}%</td>
                                    <td class="font-bold text-success">
                                        @php
                                            $totalApplicationAmount = \App\Models\Payment::where(
                                                'application_id',
                                                $commission->payment->application_id,
                                            )
                                                ->where('payment_status', 'pending')
                                                ->orWhere('payment_status', 'completed')
                                                ->sum('amount');
                                            $commissionAmountCalculated =
                                                ($totalApplicationAmount * $commission->percentage) / 100;
                                        @endphp
                                        {{ get_setting('currency_symbol', 'BDT') }}
                                        {{ number_format($commissionAmountCalculated, 2) }} Tk
                                    </td>
                                    <td>
                                        <label
                                            class="relative inline-flex cursor-pointer items-center commission-toggle-wrapper"
                                            data-commission-id="{{ $commission->id }}">
                                            <input type="checkbox" class="commission-status-toggle sr-only"
                                                data-commission-id="{{ $commission->id }}"
                                                {{ $commission->status === 'paid' ? 'checked' : '' }} />
                                            <div class="toggle-bg relative inline-flex h-6 w-11 items-center rounded-full transition-colors"
                                                style="background-color: {{ $commission->status === 'paid' ? '#10b981' : '#d1d5db' }}">
                                                <span
                                                    class="toggle-dot absolute inline-block h-4 w-4 transform rounded-full bg-white transition-transform"
                                                    style="left: {{ $commission->status === 'paid' ? '1.375rem' : '0.25rem' }}"></span>
                                            </div>
                                            <span class="ml-3 text-sm font-medium capitalize status-text">
                                                {{ $commission->status }}
                                            </span>
                                        </label>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="8" class="text-center">No commissions recorded yet.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $commissions->links() }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggles = document.querySelectorAll('.commission-status-toggle');

            toggles.forEach(toggle => {
                toggle.addEventListener('change', function() {
                    const commissionId = this.dataset.commissionId;
                    const newStatus = this.checked ? 'paid' : 'pending';
                    const wrapper = this.closest('.commission-toggle-wrapper');
                    const statusTextElement = wrapper.querySelector('.status-text');
                    const toggleBg = wrapper.querySelector('.toggle-bg');
                    const toggleDot = wrapper.querySelector('.toggle-dot');

                    fetch(`{{ route('admin.commissions.update-status', ':id') }}`.replace(':id',
                            commissionId), {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]')?.content ||
                                    '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                status: newStatus
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Update text
                                statusTextElement.textContent = data.status;
                                // Update toggle styles
                                if (data.status === 'paid') {
                                    toggleBg.style.backgroundColor = '#10b981';
                                    toggleDot.style.left = '1.375rem';
                                } else {
                                    toggleBg.style.backgroundColor = '#d1d5db';
                                    toggleDot.style.left = '0.25rem';
                                }
                                console.log(data.message);
                            } else {
                                // Revert toggle on error
                                this.checked = !this.checked;
                                alert('Failed to update commission status');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            // Revert toggle on error
                            this.checked = !this.checked;
                            alert('Error updating commission status');
                        });
                });
            });
        });
    </script>
@endpush
