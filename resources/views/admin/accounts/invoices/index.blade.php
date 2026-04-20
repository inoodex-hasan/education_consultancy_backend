@extends('admin.layouts.master')

@section('title', 'Student Invoices')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Billing & Invoices</h2>
        <a href="{{ route('admin.invoices.create') }}" class="btn btn-primary gap-2 text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Create New Invoice
        </a>
    </div>

    <div class="panel mt-6">
        <div class="table-responsive">
            <table class="table-hover w-full table-auto">
                <thead>
                    <tr>
                        <th>Issue Date</th>
                        <th>Invoice Number</th>
                        <th>Student Name</th>
                        <th>University</th>
                        <th>Total Amount</th>
                        <th>Billing Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoices as $invoice)
                        <tr>
                            <td class="whitespace-nowrap text-xs text-white-dark">{{ $invoice->date->format('M d, Y') }}</td>
                            <td class="font-bold text-primary">
                                <a href="{{ route('admin.invoices.show', $invoice) }}">{{ $invoice->invoice_number }}</a>
                            </td>
                            <td class="font-semibold text-sm">
                                {{ $invoice->student->first_name }} {{ $invoice->student->last_name }}
                                <span class="block text-[10px] text-white-dark">{{ $invoice->student->id_number }}</span>
                            </td>
                            <td class="text-xs">{{ $invoice->university->name ?? 'N/A' }}</td>
                            <td class="font-black text-dark dark:text-white-light font-mono">
                                {{ number_format($invoice->total_amount, 2) }}</td>
                            <td>
                                @if($invoice->status == 'paid')
                                    <span class="badge badge-outline-success uppercase text-[10px] font-black">Fully Paid</span>
                                @elseif($invoice->status == 'partial')
                                    <span class="badge badge-outline-warning uppercase text-[10px] font-black">Partial Paid</span>
                                @else
                                    <span class="badge badge-outline-danger uppercase text-[10px] font-black">Unpaid</span>
                                @endif
                            </td>
                            <td class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.invoices.show', $invoice) }}"
                                    class="btn btn-sm btn-outline-primary">View</a>

                                <a href="{{ route('admin.invoices.download-pdf', $invoice) }}"
                                    class="btn btn-sm btn-outline-success">PDF</a>

                                <a href="{{ route('admin.invoices.edit', $invoice) }}"
                                    class="btn btn-sm btn-outline-warning">Edit</a>

                                <form action="{{ route('admin.invoices.destroy', $invoice) }}" method="POST"
                                    class="inline-block" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-gray-400 py-16">
                                <div class="flex flex-col items-center">
                                    <div class="p-4 bg-primary/5 rounded-full mb-3">
                                        <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="1" opacity="0.3">
                                            <path
                                                d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                        </svg>
                                    </div>
                                    <p class="text-sm font-semibold tracking-widest uppercase">No Invoices Found</p>
                                    <p class="text-xs text-white-dark mt-1">Start by billing a student or university.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $invoices->links() }}
        </div>
    </div>
@endsection