@extends('admin.layouts.master')

@section('title', 'Office Transactions')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Office Transactions</h2>
        <div class="flex w-full flex-wrap items-center justify-end gap-4 sm:w-auto">
            <a href="{{ route('admin.office-transactions.create') }}" class="btn btn-primary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Add Transaction
            </a>
        </div>
    </div>

    <div class="panel mt-6">
        <div class="mb-5 flex flex-col gap-5 md:flex-row md:items-center">
            <form action="{{ route('admin.office-transactions.index') }}" method="GET"
                class="flex flex-1 flex-col gap-5 md:flex-row md:items-center w-full">
                <div class="relative w-full md:w-80">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search reference, notes..." class="form-input ltr:pr-11 rtl:pl-11" />
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
                <div class="flex gap-2">
                    <select name="transaction_type" class="form-select w-full md:w-32 pr-10">
                        <option value="">Type</option>
                        <option value="transfer" {{ request('transaction_type') == 'transfer' ? 'selected' : '' }}>Transfer
                        </option>
                        <option value="deposit" {{ request('transaction_type') == 'deposit' ? 'selected' : '' }}>Deposit
                        </option>
                        <option value="withdrawal" {{ request('transaction_type') == 'withdrawal' ? 'selected' : '' }}>
                            Withdrawal</option>
                        <option value="income" {{ request('transaction_type') == 'income' ? 'selected' : '' }}>Income
                        </option>
                        <option value="expense" {{ request('transaction_type') == 'expense' ? 'selected' : '' }}>Expense
                        </option>
                    </select>
                    <select name="account_id" class="form-select w-full md:w-40 pr-10">
                        <option value="">Account</option>
                        @foreach ($accounts as $account)
                            <option value="{{ $account->id }}"
                                {{ request('account_id') == $account->id ? 'selected' : '' }}>
                                {{ $account->account_name }} -
                                {{ $account->account_type }} - {{ $account->account_number }} </option>
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('admin.office-transactions.index') }}" class="btn btn-outline-danger">Reset</a>
                </div>
            </form>
        </div>

        <div class="datatable">
            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>From Account</th>
                            <th>To Account</th>
                            <th>Amount</th>
                            {{-- <th>Balance After</th> --}}
                            <th>Reference</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $transaction)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d M, Y') }}</td>
                                <td>
                                    @php
                                        $typeClass =
                                            [
                                                'transfer' => 'badge-outline-primary',
                                                'deposit' => 'badge-outline-success',
                                                'withdrawal' => 'badge-outline-danger',
                                                'income' => 'badge-outline-success',
                                                'expense' => 'badge-outline-danger',
                                            ][$transaction->transaction_type] ?? 'badge-outline-secondary';
                                    @endphp
                                    <span
                                        class="badge {{ $typeClass }} uppercase">{{ $transaction->transaction_type }}</span>
                                </td>
                                <td>
                                    {{ $transaction->fromAccount ? $transaction->fromAccount->account_name . ' (' . strtoupper($transaction->fromAccount->account_type) . ')' : 'N/A' }}
                                </td>
                                <td>
                                    {{ $transaction->toAccount ? $transaction->toAccount->account_name . ' (' . strtoupper($transaction->toAccount->account_type) . ')' : 'N/A' }}
                                </td>
                                <td class="font-semibold">{{ number_format($transaction->amount, 2) }}</td>
                                {{-- <td>
                                    @if ($transaction->transaction_type === 'income' || $transaction->transaction_type === 'deposit')
                                        <span class="text-success font-semibold">
                                            {{ $transaction->to_balance_after !== null ? number_format((float) $transaction->to_balance_after, 2) : 'N/A' }}
                                        </span>
                                    @elseif($transaction->transaction_type === 'expense' || $transaction->transaction_type === 'withdrawal')
                                        <span class="text-danger font-semibold">
                                            {{ $transaction->from_balance_after !== null ? number_format((float) $transaction->from_balance_after, 2) : 'N/A' }}
                                        </span>
                                    @elseif($transaction->transaction_type === 'transfer')
                                        <div class="text-xs">
                                            <div class="text-danger">
                                                From: {{ $transaction->from_balance_after !== null ? number_format((float) $transaction->from_balance_after, 2) : 'N/A' }}
                                            </div>
                                            <div class="text-success">
                                                To: {{ $transaction->to_balance_after !== null ? number_format((float) $transaction->to_balance_after, 2) : 'N/A' }}
                                            </div>
                                        </div>
                                    @else
                                        N/A
                                    @endif
                                </td> --}}
                                <td>{{ $transaction->reference ?? 'N/A' }}</td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.office-transactions.show', $transaction->id) }}"
                                            class="btn btn-sm btn-outline-info">View</a>
                                        <form action="{{ route('admin.office-transactions.destroy', $transaction->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Delete this transaction? Account balances will be reverted automatically.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No transactions found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
@endsection
