@extends('admin.layouts.master')

@section('title', 'Edit Application')

@section('content')
    <div>
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold uppercase">Edit Application: {{ $application->application_id }}</h2>
        </div>

        <div class="panel mt-6">
            <form action="{{ route('admin.applications.update2', $application->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div class="form-group">
                        <label for="student_id">Student</label>
                        <select name="student_id" id="student_id" class="form-select bg-gray-100 dark:bg-black/20" disabled>
                            <option value="{{ $application->student_id }}">
                                {{ $application->student->first_name }} {{ $application->student->last_name }}
                            </option>
                        </select>
                        <span class="text-xs text-white-dark mt-1">Student cannot be changed once application is
                            created.</span>
                    </div>

                    <div class="form-group">
                        <label for="country_id">Country</label>
                        <select name="country_id" id="country_id" class="form-select">
                            <option value="">Select Country</option>
                            @foreach ($countries as $country)
                                <option value="{{ $country->id }}"
                                    {{ old('country_id', $application->university->country_id ?? '') == $country->id ? 'selected' : '' }}>
                                    {{ $country->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="university_id">Select University</label>
                        <select name="university_id" id="university_id" class="form-select" readonly>
                            <option value="">Select University</option>
                            @foreach ($universities as $university)
                                <option value="{{ $university->id }}"
                                    {{ old('university_id', $application->university_id) == $university->id ? 'selected' : '' }}>
                                    {{ $university->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('university_id')
                            <span class="text-danger text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="course_id">Select Course</label>
                        <select name="course_id" id="course_id" class="form-select" readonly>
                            <option value="">Select Course</option>
                            @foreach ($courses as $course)
                                <option value="{{ $course->id }}" data-tuition-fee="{{ $course->tuition_fee }}"
                                    data-currency="{{ $course->currency }}"
                                    data-exchange-rate="{{ $course->exchange_rate }}"
                                    {{ old('course_id', $application->course_id) == $course->id ? 'selected' : '' }}>
                                    {{ $course->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('course_id')
                            <span class="text-danger text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="course_intake_id">Select Intake</label>
                        <select name="course_intake_id" id="course_intake_id" class="form-select" readonly>
                            <option value="">Select Intake</option>
                            @foreach ($intakes as $intake)
                                <option value="{{ $intake->id }}"
                                    {{ old('course_intake_id', $application->course_intake_id) == $intake->id ? 'selected' : '' }}>
                                    {{ $intake->intake_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('course_intake_id')
                            <span class="text-danger text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                        <div class="form-group">
                            <label for="tuition_fee">Tuition Fee</label>
                            <input type="number" name="tuition_fee" id="tuition_fee" class="form-input"
                                value="{{ old('tuition_fee', $application->tuition_fee) }}" readonly>
                            @error('tuition_fee')
                                <span class="text-danger text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="currency">Currency</label>
                            <input type="text" name="currency" id="currency"
                                class="form-input bg-[#f1f2f3] dark:bg-[#1b2e4b]"
                                value="{{ old('currency', $application->currency) }}" readonly>
                            @error('currency')
                                <span class="text-danger text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="bdt_amount">Equivalent (BDT)</label>
                            <input type="text" id="bdt_amount" class="form-input bg-[#f1f2f3] dark:bg-[#1b2e4b]"
                                value="0.00" readonly>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="total_fee">Total Fee <span class="text-danger">*</span></label>
                        <input type="number" name="total_fee" id="total_fee" step="0.01" class="form-input"
                            value="{{ old('total_fee', $application->total_fee) }}" required>
                        @error('total_fee')
                            <span class="text-danger text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="status">Application Status</label>
                        <select name="status" id="status" class="form-select" required>
                            @foreach (['pending', 'ready_for_apply', 'applied', 'under_review', 'offer_issued', 'conditional_offer', 'unconditional_offer', 'rejected', 'withdrawn', 'visa_processing', 'enrolled'] as $status)
                                <option value="{{ $status }}"
                                    {{ old('status', $application->status) == $status ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $status)) }}
                                </option>
                            @endforeach
                        </select>
                        @error('status')
                            <span class="text-danger text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group md:col-span-2">
                        <label for="notes">Notes</label>
                        <textarea name="notes" id="notes" class="form-input" rows="3" placeholder="Additional information...">{{ old('notes', $application->notes) }}</textarea>
                        @error('notes')
                            <span class="text-danger text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-4">
                    <a href="{{ route('admin.applications.index2') }}" class="btn btn-outline-danger">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Application</button>
                </div>
            </form>
            <div class="panel mt-6">
                <div class="flex items-center justify-between mb-5">
                    <h5 class="text-lg font-semibold dark:text-white-light uppercase">Payment History</h5>
                    <!-- <a href="{{ route('admin.payments.create', ['application_id' => $application->id]) }}" class="btn btn-primary btn-sm">Add Payment</a> -->
                </div>
                <div class="table-responsive">
                    <table class="table-hover">
                        <thead>
                            <tr>
                                <th>Receipt No</th>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Collected By</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($application->payments as $payment)
                                <tr>
                                    <td>{{ $payment->receipt_number }}</td>
                                    <td>{{ $payment->payment_date ? $payment->payment_date->format('M d, Y') : '-' }}</td>
                                    <td class="capitalize">{{ $payment->payment_type }}</td>
                                    <td>BDT {{ number_format($payment->amount, 2) }}</td>
                                    <td>
                                        <span
                                            class="badge {{ $payment->payment_status === 'completed' ? 'badge-outline-success' : 'badge-outline-warning' }}">
                                            {{ $payment->payment_status }}
                                        </span>
                                    </td>
                                    <td>{{ $payment->collector->name ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No payment history found for this application.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endsection

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const countrySelect = document.getElementById('country_id');
                const universitySelect = document.getElementById('university_id');
                const courseSelect = document.getElementById('course_id');
                const intakeSelect = document.getElementById('course_intake_id');
                const tuitionFeeInput = document.getElementById('tuition_fee');
                const currencyInput = document.getElementById('currency');
                const bdtAmountInput = document.getElementById('bdt_amount');
                const totalFeeInput = document.getElementById('total_fee');

                function toggleAcademicFields(disabled) {
                    const fields = [countrySelect, universitySelect, courseSelect, intakeSelect];
                    fields.forEach(select => {
                        select.disabled = disabled;
                        // Manage hidden inputs for disabled fields to ensure submission
                        let hiddenInput = document.getElementById('hidden_' + select.name);
                        if (disabled) {
                            if (!hiddenInput) {
                                hiddenInput = document.createElement('input');
                                hiddenInput.type = 'hidden';
                                hiddenInput.name = select.name;
                                hiddenInput.id = 'hidden_' + select.name;
                                select.after(hiddenInput);
                            }
                            hiddenInput.value = select.value;
                        } else {
                            if (hiddenInput) hiddenInput.remove();
                        }
                    });
                }

                function calculateBDT() {
                    const selectedOption = courseSelect.options[courseSelect.selectedIndex];
                    if (selectedOption && selectedOption.dataset.tuitionFee) {
                        if (selectedOption.dataset.exchangeRate && selectedOption.dataset.exchangeRate > 0) {
                            const bdtAmount = selectedOption.dataset.tuitionFee / selectedOption.dataset.exchangeRate;
                            bdtAmountInput.value = bdtAmount.toFixed(2);
                        }
                    } else {
                        bdtAmountInput.value = '0.00';
                    }
                }

                countrySelect.addEventListener('change', function() {
                    const countryId = this.value;
                    universitySelect.innerHTML = '<option value="">Select University</option>';
                    courseSelect.innerHTML = '<option value="">Select Course</option>';
                    intakeSelect.innerHTML = '<option value="">Select Intake</option>';

                    if (countryId) {
                        fetch(`{{ route('admin.applications.get-universities') }}?country_id=${countryId}`)
                            .then(response => response.json())
                            .then(data => {
                                data.forEach(university => {
                                    const option = document.createElement('option');
                                    option.value = university.id;
                                    option.textContent = university.name;
                                    universitySelect.appendChild(option);
                                });
                            });
                    }
                });

                universitySelect.addEventListener('change', function() {
                    const universityId = this.value;
                    courseSelect.innerHTML = '<option value="">Select Course</option>';
                    intakeSelect.innerHTML = '<option value="">Select Intake</option>';

                    if (universityId) {
                        fetch(`{{ route('admin.applications.get-courses') }}?university_id=${universityId}`)
                            .then(response => response.json())
                            .then(data => {
                                data.forEach(course => {
                                    const option = document.createElement('option');
                                    option.value = course.id;
                                    option.textContent = course.name;
                                    option.dataset.tuitionFee = course.tuition_fee;
                                    option.dataset.currency = course.currency;
                                    option.dataset.exchangeRate = course.exchange_rate;
                                    courseSelect.appendChild(option);
                                });
                            });
                    }
                });

                courseSelect.addEventListener('change', function() {
                    const courseId = this.value;
                    intakeSelect.innerHTML = '<option value="">Select Intake</option>';
                    tuitionFeeInput.value = '';
                    currencyInput.value = '';

                    const selectedOption = this.options[this.selectedIndex];
                    if (selectedOption && selectedOption.dataset.tuitionFee) {
                        tuitionFeeInput.value = selectedOption.dataset.tuitionFee;
                        if (selectedOption.dataset.exchangeRate && selectedOption.dataset.exchangeRate > 0) {
                            const bdtAmount = selectedOption.dataset.tuitionFee / selectedOption.dataset
                                .exchangeRate;
                            bdtAmountInput.value = bdtAmount.toFixed(2);
                            totalFeeInput.value = bdtAmount.toFixed(2);
                        }
                    }
                    if (selectedOption && selectedOption.dataset.currency) {
                        currencyInput.value = selectedOption.dataset.currency;
                    }

                    if (courseId) {
                        fetch(`{{ route('admin.applications.get-intakes') }}?course_id=${courseId}`)
                            .then(response => response.json())
                            .then(data => {
                                data.forEach(intake => {
                                    const option = document.createElement('option');
                                    option.value = intake.id;
                                    option.textContent = intake.intake_name;
                                    intakeSelect.appendChild(option);
                                });
                            });
                    }
                });

                // Initial calculation
                calculateBDT();
            });
        </script>
    @endpush
