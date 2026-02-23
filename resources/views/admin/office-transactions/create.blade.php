@extends('admin.layouts.master')

@section('title', 'Add Office Transaction')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Add Office Transaction</h2>
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
        <form action="{{ route('admin.office-transactions.store') }}" method="POST" id="transaction-form">
            @csrf
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="form-group">
                    <label for="transaction_type">Transaction Type <span class="text-danger">*</span></label>
                    <select name="transaction_type" id="transaction_type" class="form-select" required>
                        <option value="">Select Type</option>
                        <option value="transfer" {{ old('transaction_type') == 'transfer' ? 'selected' : '' }}>Transfer
                        </option>
                        <option value="deposit" {{ old('transaction_type') == 'deposit' ? 'selected' : '' }}>Deposit</option>
                        <option value="withdrawal" {{ old('transaction_type') == 'withdrawal' ? 'selected' : '' }}>Withdrawal
                        </option>
                    </select>
                    @error('transaction_type') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="transaction_date">Date <span class="text-danger">*</span></label>
                    <input type="date" name="transaction_date" id="transaction_date" class="form-input"
                        value="{{ old('transaction_date', date('Y-m-d')) }}" required>
                    @error('transaction_date') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-group" id="from_account_wrapper">
                    <label for="from_account_id">From Account <span class="text-danger">*</span></label>
                    <select name="from_account_id" id="from_account_id" class="form-select">
                        <option value="">Select Account</option>
                        @foreach($accounts as $account)
                            <option value="{{ $account->id }}" {{ old('from_account_id') == $account->id ? 'selected' : '' }}>
                                {{ $account->account_name }} ({{ strtoupper($account->account_type) }})
                            </option>
                        @endforeach
                    </select>
                    @error('from_account_id') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-group" id="to_account_wrapper">
                    <label for="to_account_id">To Account <span class="text-danger">*</span></label>
                    <select name="to_account_id" id="to_account_id" class="form-select">
                        <option value="">Select Account</option>
                        @foreach($accounts as $account)
                            <option value="{{ $account->id }}" {{ old('to_account_id') == $account->id ? 'selected' : '' }}>
                                {{ $account->account_name }} ({{ strtoupper($account->account_type) }})
                            </option>
                        @endforeach
                    </select>
                    @error('to_account_id') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="amount">Amount <span class="text-danger">*</span></label>
                    <input type="number" name="amount" id="amount" class="form-input" step="0.01"
                        value="{{ old('amount') }}" required>
                    @error('amount') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="reference">Reference / TXN ID</label>
                    <input type="text" name="reference" id="reference" class="form-input" value="{{ old('reference') }}"
                        placeholder="e.g., TXN-12345">
                    @error('reference') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-group md:col-span-2">
                    <label for="notes">Notes</label>
                    <textarea name="notes" id="notes" class="form-textarea" rows="3">{{ old('notes') }}</textarea>
                    @error('notes') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-4">
                <button type="reset" class="btn btn-outline-danger">Reset Form</button>
                <button type="submit" class="btn btn-primary px-10">Record Transaction</button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const typeSelect = document.getElementById('transaction_type');
            const fromWrapper = document.getElementById('from_account_wrapper');
            const toWrapper = document.getElementById('to_account_wrapper');
            const fromSelect = document.getElementById('from_account_id');
            const toSelect = document.getElementById('to_account_id');

            function toggleFields() {
                const type = typeSelect.value;
                if (type === 'transfer') {
                    fromWrapper.style.display = 'block';
                    toWrapper.style.display = 'block';
                    fromSelect.required = true;
                    toSelect.required = true;
                } else if (type === 'deposit') {
                    fromWrapper.style.display = 'none';
                    toWrapper.style.display = 'block';
                    fromSelect.required = false;
                    toSelect.required = true;
                } else if (type === 'withdrawal') {
                    fromWrapper.style.display = 'block';
                    toWrapper.style.display = 'none';
                    fromSelect.required = true;
                    toSelect.required = false;
                } else {
                    fromWrapper.style.display = 'block';
                    toWrapper.style.display = 'block';
                }
            }

            typeSelect.addEventListener('change', toggleFields);
            toggleFields(); // Initial call
        });
    </script>
@endsection