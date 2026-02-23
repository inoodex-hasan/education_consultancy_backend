@extends('admin.layouts.master')

@section('title', 'Transaction Details')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Transaction Details</h2>
        <a href="{{ route('admin.office-transactions.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to List
        </a>
    </div>

    <div class="panel mt-6">
        <div class="mb-5">
            <h5 class="text-lg font-semibold dark:text-white-light">General Information</h5>
        </div>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <div class="space-y-1">
                <p class="text-white-dark">Reference / TXN ID</p>
                <p class="font-semibold">{{ $officeTransaction->reference ?? 'N/A' }}</p>
            </div>
            <div class="space-y-1">
                <p class="text-white-dark">Transaction Date</p>
                <p class="font-semibold">{{ \Carbon\Carbon::parse($officeTransaction->transaction_date)->format('d M, Y') }}
                </p>
            </div>
            <div class="space-y-1">
                <p class="text-white-dark">Transaction Type</p>
                @php
                    $typeClass = [
                        'transfer' => 'badge-outline-primary',
                        'deposit' => 'badge-outline-success',
                        'withdrawal' => 'badge-outline-danger',
                        'income' => 'badge-outline-success',
                        'expense' => 'badge-outline-danger',
                    ][$officeTransaction->transaction_type] ?? 'badge-outline-secondary';
                @endphp
                <span class="badge {{ $typeClass }} uppercase">{{ $officeTransaction->transaction_type }}</span>
            </div>
            <div class="space-y-1">
                <p class="text-white-dark">Amount</p>
                <p class="text-lg font-bold text-primary">{{ number_format($officeTransaction->amount, 2) }}</p>
            </div>
            <div class="space-y-1">
                <p class="text-white-dark">Recorded By</p>
                <p class="font-semibold">{{ $officeTransaction->creator->name ?? 'System' }}</p>
            </div>
            <div class="space-y-1">
                <p class="text-white-dark">Recorded At</p>
                <p class="font-semibold">{{ $officeTransaction->created_at->format('d M, Y H:i A') }}</p>
            </div>
        </div>

        <hr class="my-6 border-white-light dark:border-[#1b2e4b]" />

        <div class="mb-5 text-lg font-semibold dark:text-white-light">Account Details</div>
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            @if(in_array($officeTransaction->transaction_type, ['transfer', 'withdrawal', 'expense']))
                <div class="rounded-md border border-white-light bg-danger-light p-4 dark:border-[#1b2e4b] dark:bg-[#191e3a]">
                    <h6 class="mb-3 font-semibold uppercase text-danger">From Account (Source)</h6>
                    @if($officeTransaction->fromAccount)
                        <div class="space-y-2">
                            <p><span class="text-white-dark">Name:</span> <span
                                    class="font-semibold">{{ $officeTransaction->fromAccount->account_name }}</span></p>
                            <p><span class="text-white-dark">Type:</span> <span
                                    class="badge badge-outline-danger btn-sm uppercase">{{ $officeTransaction->fromAccount->account_type }}</span>
                            </p>
                            <p><span class="text-white-dark">Provider:</span>
                                <span>{{ $officeTransaction->fromAccount->provider_name ?? 'N/A' }}</span>
                            </p>
                            <p><span class="text-white-dark">Number:</span> <span
                                    class="font-mono text-sm underline">{{ $officeTransaction->fromAccount->account_number }}</span>
                            </p>
                        </div>
                    @else
                        <p class="italic text-white-dark">Account no longer exists.</p>
                    @endif
                </div>
            @endif

            @if(in_array($officeTransaction->transaction_type, ['transfer', 'deposit', 'income']))
                <div class="rounded-md border border-white-light bg-success-light p-4 dark:border-[#1b2e4b] dark:bg-[#191e3a]">
                    <h6 class="mb-3 font-semibold uppercase text-success">To Account (Destination)</h6>
                    @if($officeTransaction->toAccount)
                        <div class="space-y-2">
                            <p><span class="text-white-dark">Name:</span> <span
                                    class="font-semibold">{{ $officeTransaction->toAccount->account_name }}</span></p>
                            <p><span class="text-white-dark">Type:</span> <span
                                    class="badge badge-outline-success btn-sm uppercase">{{ $officeTransaction->toAccount->account_type }}</span>
                            </p>
                            <p><span class="text-white-dark">Provider:</span>
                                <span>{{ $officeTransaction->toAccount->provider_name ?? 'N/A' }}</span>
                            </p>
                            <p><span class="text-white-dark">Number:</span> <span
                                    class="font-mono text-sm underline">{{ $officeTransaction->toAccount->account_number }}</span>
                            </p>
                        </div>
                    @else
                        <p class="italic text-white-dark">Account no longer exists.</p>
                    @endif
                </div>
            @endif
        </div>

        <hr class="my-6 border-white-light dark:border-[#1b2e4b]" />

        <div class="mb-2 text-lg font-semibold dark:text-white-light">Notes</div>
        <div class="rounded-md bg-[#f1f2f3] p-4 dark:bg-[#0e1726]">
            {{ $officeTransaction->notes ?? 'No notes provided.' }}
        </div>
    </div>
@endsection
