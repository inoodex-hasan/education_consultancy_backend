@extends('admin.layouts.master')

@section('title', 'Create Payment')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/nice-select2.css') }}">
    <style>
        .nice-select {
            width: 100%;
            height: 42px !important;
            display: flex !important;
            align-items: center !important;
        }

        .nice-select .current {
            line-height: normal !important;
            display: flex !important;
            align-items: center !important;
            height: 100% !important;
        }

        .nice-select .list {
            width: 100%;
        }

        /* Fix double arrow issue */
        .nice-select {
            background-image: none !important;
        }

        .form-select {
            background-image: none !important;
        }
    </style>
@endpush

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Create Payment</h2>
        <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to List
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.payments.store') }}" method="POST" id="paymentForm">
            @csrf
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div class="form-group md:col-span-2">
                    <label for="application_id">Application <span class="text-danger">*</span></label>
                    <select name="application_id" id="application_id" class="form-select" required>
                        <option value="">Select Application (Search by ID or Student Name)</option>
                        @foreach ($applications as $app)
                            <option value="{{ $app->id }}" {{ old('application_id', $selected_application_id) == $app->id ? 'selected' : '' }}>
                                {{ $app->application_id }} - {{ $app->student->first_name }} {{ $app->student->last_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('application_id')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div id="balance_info_container" class="md:col-span-2 hidden">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 bg-primary/5 p-4 rounded-lg border border-primary/20">
                        <div class="flex flex-col">
                            <span class="text-xs text-white-dark uppercase">Total Fee</span>
                            <span id="info_total_fee" class="text-lg font-bold text-primary">BDT 0.00</span>
                        </div>
                        <div class="flex flex-col border-l border-r border-white-light/20 px-4">
                            <span class="text-xs text-white-dark uppercase">Total Paid</span>
                            <span id="info_total_paid" class="text-lg font-bold text-success">BDT 0.00</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xs text-white-dark uppercase">Remaining Balance</span>
                            <span id="info_balance" class="text-lg font-bold text-danger">BDT 0.00</span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="amount">Amount <span class="text-danger">*</span></label>
                    <input type="number" name="amount" id="amount" class="form-input" required step="0.01" min="0"
                        value="{{ old('amount') }}" />
                    @error('amount')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="payment_type">Payment Type <span class="text-danger">*</span></label>
                    <select name="payment_type" id="payment_type" class="form-select" required>
                        <option value="advance" {{ old('payment_type') == 'advance' ? 'selected' : '' }}>Advance</option>
                        <option value="partial" {{ old('payment_type') == 'partial' ? 'selected' : '' }}>Partial</option>
                        <option value="final" {{ old('payment_type') == 'final' ? 'selected' : '' }}>Final</option>
                    </select>
                    @error('payment_type')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="payment_status">Payment Status <span class="text-danger">*</span></label>
                    <select name="payment_status" id="payment_status" class="form-select" required>
                        <option value="pending" {{ old('payment_status', 'pending') == 'pending' ? 'selected' : '' }}>
                            Pending
                        </option>
                        <option value="completed" {{ old('payment_status') == 'completed' ? 'selected' : '' }}>Completed
                        </option>
                    </select>
                    @error('payment_status')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="payment_date">Payment Date</label>
                    <input type="date" name="payment_date" id="payment_date" class="form-input"
                        value="{{ old('payment_date', date('Y-m-d')) }}">
                    @error('payment_date')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="office_account_id">Collect To Account</label>
                    <select name="office_account_id" id="office_account_id" class="form-select">
                        <option value="">-- Select Account --</option>
                        @foreach ($accounts as $account)
                            <option value="{{ $account->id }}" {{ old('office_account_id') == $account->id ? 'selected' : '' }}>
                                {{ $account->account_name }}
                                ({{ ucfirst($account->account_type) }}{{ $account->provider_name ? ' - ' . $account->provider_name : '' }}
                                - {{ $account->account_number }})
                            </option>
                        @endforeach
                    </select>
                    @error('office_account_id')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group md:col-span-2">
                    <label for="notes">Notes</label>
                    <textarea name="notes" id="notes" class="form-input" rows="3"
                        placeholder="Additional information...">{{ old('notes') }}</textarea>
                    @error('notes')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-4">
                <button type="reset" class="btn btn-outline-danger" id="resetBtn">Reset Form</button>
                <button type="submit" class="btn btn-primary px-10" id="submitBtn">Save Payment</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/nice-select2.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const applicationSelect = document.getElementById('application_id');
            const amountInput = document.getElementById('amount');
            const paymentTypeSelect = document.getElementById('payment_type');
            const paymentStatusSelect = document.getElementById('payment_status');
            const balanceInfoContainer = document.getElementById('balance_info_container');
            const infoTotalFee = document.getElementById('info_total_fee');
            const infoTotalPaid = document.getElementById('info_total_paid');
            const infoBalance = document.getElementById('info_balance');
            const submitBtn = document.getElementById('submitBtn');

            // Initialize NiceSelect2
            const niceSelect = NiceSelect.bind(applicationSelect, {
                searchable: true,
                placeholder: 'Select Application (Search by ID or Student Name)'
            });

            function updateBalance() {
                const applicationId = applicationSelect.value;
                if (!applicationId) {
                    balanceInfoContainer.classList.add('hidden');
                    return;
                }

                fetch(`{{ route('admin.payments.get-application-balance') }}?application_id=${applicationId}`)
                    .then(response => response.json())
                    .then(data => {
                        balanceInfoContainer.classList.remove('hidden');
                        infoTotalFee.textContent =
                            `BDT ${parseFloat(data.total_fee).toLocaleString(undefined, { minimumFractionDigits: 2 })}`;
                        infoTotalPaid.textContent =
                            `BDT ${parseFloat(data.total_paid).toLocaleString(undefined, { minimumFractionDigits: 2 })}`;
                        infoBalance.textContent =
                            `BDT ${parseFloat(data.balance).toLocaleString(undefined, { minimumFractionDigits: 2 })}`;

                        if (data.balance <= 0) {
                            amountInput.value = '0.00';
                            amountInput.disabled = true;
                            submitBtn.disabled = true;
                            submitBtn.textContent = 'Payment Completed';

                            // Visual feedback for completed status
                            infoBalance.classList.remove('text-danger');
                            infoBalance.classList.add('text-success');
                        } else {
                            amountInput.disabled = false;
                            submitBtn.disabled = false;
                            submitBtn.textContent = 'Save Payment';
                            amountInput.value = data.balance.toFixed(2);
                            paymentTypeSelect.value = 'final';

                            infoBalance.classList.remove('text-success');
                            infoBalance.classList.add('text-danger');
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching balance:', error);
                    });
            }

            applicationSelect.addEventListener('change', updateBalance);

            // Trigger once if application is pre-selected (e.g. from history view or old input)
            if (applicationSelect.value) {
                updateBalance();
            }

            document.getElementById('paymentForm').addEventListener('submit', function () {
                // Re-enable if disabled so it submits
                amountInput.disabled = false;
            });

            document.getElementById('resetBtn').addEventListener('click', function () {
                setTimeout(() => {
                    niceSelect.update();
                    balanceInfoContainer.classList.add('hidden');
                }, 10);
            });
        });
    </script>
@endpush