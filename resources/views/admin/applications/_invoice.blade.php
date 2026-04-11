@extends('admin.layouts.master')

@section('title', 'Generate Invoice')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Generate Invoice: {{ $application->application_id }}</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.applications.index') }}" class="btn btn-outline-danger">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Back
            </a>
            @canany(['*consultant'])
                <a href="{{ route('admin.applications.edit', $application->id) }}" class="btn btn-outline-primary">Edit Application</a>
            @endcanany
        </div>
    </div>

    <div class="panel mt-6">
        <div class="mb-8">
            <div class="text-center mb-6">
                <h1 class="text-2xl font-bold text-primary uppercase">Invoice</h1>
                <p class="text-gray-500">Application: <span class="font-semibold text-primary">{{ $application->application_id }}</span></p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                {{-- Student Info --}}
                <div>
                    <h5 class="text-lg font-semibold text-primary uppercase mb-3">Student Information</h5>
                    <div class="space-y-2">
                        <p><span class="text-white-dark">Name:</span> <span class="font-semibold">{{ $application->student->first_name }} {{ $application->student->last_name }}</span></p>
                        <p><span class="text-white-dark">Email:</span> {{ $application->student->email ?? '-' }}</p>
                        <p><span class="text-white-dark">Phone:</span> {{ $application->student->phone ?? '-' }}</p>
                        <p><span class="text-white-dark">Passport:</span> {{ $application->student->passport_number ?? '-' }}</p>
                    </div>
                </div>

                {{-- University Info --}}
                <div>
                    <h5 class="text-lg font-semibold text-primary uppercase mb-3">University Details</h5>
                    <div class="space-y-2">
                        <p><span class="text-white-dark">Country:</span> {{ $application->university->country->name ?? 'N/A' }}</p>
                        <p><span class="text-white-dark">University:</span> {{ $application->university->name ?? 'N/A' }}</p>
                        <p><span class="text-white-dark">Course:</span> {{ $application->course->name ?? 'N/A' }}</p>
                        <p><span class="text-white-dark">Intake:</span> {{ $application->intake->intake_name ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            {{-- Fee Breakdown --}}
            <div class="mb-8">
                <h5 class="text-lg font-semibold text-primary uppercase mb-3">Fee Breakdown</h5>
                <div class="overflow-x-auto">
                    <table class="table-hover w-full table-auto">
                        <thead>
                            <tr>
                                <th>Description</th>
                                <th class="text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Tuition Fee</td>
                                <td class="text-right">{{ number_format($application->tuition_fee ?? 0, 2) }}</td>
                            </tr>
                            @if($application->service_charge_status !== 'waived')
                                <tr>
                                    <td>Service Charge ({{ ucfirst($application->service_charge_status ?? 'pending') }})</td>
                                    <td class="text-right">{{ number_format($application->service_charge ?? 0, 2) }}</td>
                                </tr>
                            @endif
                            <tr class="border-t-2 border-primary font-bold">
                                <td>Total Amount</td>
                                <td class="text-right text-primary">{{ number_format(($application->tuition_fee ?? 0) + ($application->service_charge ?? 0), 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Payment History --}}
            <div class="mb-8">
                <h5 class="text-lg font-semibold text-primary uppercase mb-3">Payment History</h5>
                @if($application->payments->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="table-hover w-full table-auto">
                            <thead>
                                <tr>
                                    <th>Receipt No</th>
                                    <th>Date</th>
                                    <th>Method</th>
                                    <th class="text-right">Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($application->payments as $payment)
                                    <tr>
                                        <td>{{ $payment->receipt_number }}</td>
                                        <td>{{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') : '-' }}</td>
                                        <td class="capitalize">{{ $payment->payment_method ?? '-' }}</td>
                                        <td class="text-right">{{ number_format($payment->amount, 2) }}</td>
                                        <td>
                                            <span class="badge badge-outline-{{ $payment->payment_status === 'completed' ? 'success' : 'warning' }}">
                                                {{ ucfirst($payment->payment_status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500 italic">No payments recorded yet.</p>
                @endif
            </div>

            {{-- Summary --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div class="p-4 bg-gray-50 dark:bg-black/20 rounded-lg">
                    <p class="text-white-dark">Total Paid:</p>
                    <p class="text-xl font-bold text-success">{{ number_format($application->payments->where('payment_status', 'completed')->sum('amount'), 2) }}</p>
                </div>
                <div class="p-4 bg-gray-50 dark:bg-black/20 rounded-lg">
                    <p class="text-white-dark">Balance Due:</p>
                    <p class="text-xl font-bold text-danger">
                        {{ number_format(max(0, ($application->tuition_fee ?? 0) + ($application->service_charge ?? 0) - $application->payments->where('payment_status', 'completed')->sum('amount')), 2) }}
                    </p>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex gap-4 justify-end">
                <button class="btn btn-outline-secondary">Print Invoice</button>
                <button class="btn btn-outline-success">Download PDF</button>
                <button class="btn btn-primary">Send to Student</button>
            </div>
        </div>
    </div>
@endsection
