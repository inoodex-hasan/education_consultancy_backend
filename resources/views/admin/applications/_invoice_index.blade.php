@extends('admin.layouts.master')

@section('title', 'Application Invoices')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Application Invoices</h2>
        @canany(['*consultant', '*application'])
            <div class="flex w-full flex-wrap items-center justify-end gap-4 sm:w-auto">
                <a href="{{ route('admin.applications.index') }}" class="btn btn-secondary gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    Back to Applications
                </a>
            </div>
        @endcanany
    </div>

    <div class="panel mt-6">
        <div class="mb-5 flex flex-col gap-5 md:flex-row md:items-center">
            <form action="{{ route('admin.applications.invoice-index') }}" method="GET"
                class="flex flex-1 flex-col gap-5 md:flex-row md:items-center w-full">
                <div class="relative flex-1">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search Invoice #, Student Name..." class="form-input ltr:pr-11 rtl:pl-11" />
                    <button type="submit"
                        class="absolute inset-y-0 flex items-center hover:text-primary ltr:right-4 rtl:left-4">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="11.5" cy="11.5" r="9.5" stroke="currentColor" stroke-width="1.5" opacity="0.5" />
                            <path d="M18.5 18.5L22 22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                    </button>
                </div>
                <div class="flex gap-2">
                    <select name="status" class="form-select w-auto md:w-auto pr-10">
                        <option value="">All Status</option>
                        <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                        <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>Partial</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                    </select>
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('admin.applications.invoice-index') }}" class="btn btn-outline-danger">Reset</a>
                </div>
            </form>
        </div>

        <div class="datatable">
            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto">
                    <thead>
                        <tr>
                            <th>Invoice #</th>
                            <th>Application ID</th>
                            <th>Student</th>
                            <th>University</th>
                            <th>Date</th>
                            <th>Due Date</th>
                            <th class="text-right">Total</th>
                            <th class="text-right">Paid</th>
                            <th class="text-right">Balance</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoices as $invoice)
                            <tr>
                                <td class="font-bold text-primary">{{ $invoice->invoice_number }}</td>
                                <td>
                                    @if($invoice->application)
                                        <a href="{{ route('admin.applications.edit', $invoice->application->id) }}" class="text-primary hover:underline">
                                            {{ $invoice->application->application_id }}
                                        </a>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($invoice->student)
                                        <div class="font-semibold">{{ $invoice->student->first_name }} {{ $invoice->student->last_name }}</div>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td>{{ $invoice->university->name ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($invoice->date)->format('M d, Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($invoice->due_date)->format('M d, Y') }}</td>
                                <td class="text-right font-semibold">{{ number_format($invoice->total_amount, 2) }}</td>
                                <td class="text-right text-success">{{ number_format($invoice->payments->sum('amount'), 2) }}</td>
                                <td class="text-right text-danger font-semibold">{{ number_format($invoice->total_amount - $invoice->payments->sum('amount'), 2) }}</td>
                                <td>
                                    @php
                                        $paid = $invoice->payments->sum('amount');
                                        $balance = $invoice->total_amount - $paid;
                                        if ($balance <= 0) {
                                            $status = 'paid';
                                            $color = 'success';
                                        } elseif ($paid > 0) {
                                            $status = 'partial';
                                            $color = 'warning';
                                        } elseif (\Carbon\Carbon::parse($invoice->due_date)->isPast()) {
                                            $status = 'overdue';
                                            $color = 'danger';
                                        } else {
                                            $status = 'unpaid';
                                            $color = 'secondary';
                                        }
                                    @endphp
                                    <span class="badge badge-outline-{{ $color }} capitalize">{{ ucfirst($status) }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.applications.invoice', $invoice->application_id ?? $invoice->id) }}"
                                            class="btn btn-sm btn-outline-info">View</a>
                                        <a href="{{ route('admin.invoices.show', $invoice->id) }}"
                                            class="btn btn-sm btn-outline-primary">Details</a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center">No invoices found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $invoices->links() }}
            </div>
        </div>
    </div>
@endsection
