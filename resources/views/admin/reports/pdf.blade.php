<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Financial Report - {{ $reportDate }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            color: #2196F3;
        }

        .section-title {
            background: #f5f5f5;
            padding: 10px;
            font-weight: bold;
            margin: 20px 0 10px;
            text-transform: uppercase;
            border-left: 4px solid #2196F3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #eee;
            padding: 8px;
            text-align: left;
        }

        th {
            background: #fafafa;
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .font-bold {
            font-weight: bold;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            border-top: 1px solid #eee;
            padding-top: 5px;
        }

        .badge {
            padding: 3px 6px;
            border-radius: 3px;
            font-size: 10px;
            color: #fff;
        }

        .bg-primary {
            background: #2196F3;
        }

        .bg-success {
            background: #4caf50;
        }

        .bg-danger {
            background: #f44336;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Monthly Financial Report</h1>
        <p>{{ $reportDate }}</p>
        @if (isset($settings['site_name']))
            <p><strong>{{ $settings['site_name'] }}</strong></p>
        @endif
    </div>

    <div class="section-title">Income Breakdown (Student Payments)</div>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Student</th>
                <th>Receipt #</th>
                <th class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            @php $totalInc = 0; @endphp
            @forelse($payments as $payment)
                @php $totalInc += $payment->amount; @endphp
                <tr>
                    <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M, Y') }}</td>
                    <td style="text-transform: uppercase">{{ $payment->student->first_name ?? 'N/A' }}
                        {{ $payment->student->last_name ?? '' }}</td>
                    <td>{{ $payment->receipt_number }}</td>
                    <td class="text-right">{{ number_format($payment->amount, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">No income recorded.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="font-bold">
                <td colspan="3" class="text-right">Total Income:</td>
                <td class="text-right">{{ number_format($totalInc, 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="section-title">Expenses Breakdown</div>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Category (Purpose)</th>
                <th>Payment Method</th>
                <th class="text-right">Amount</th>
                <th>Recorded By</th>
            </tr>
        </thead>
        <tbody>
            @php $totalExp = 0; @endphp
            @forelse($expenses as $expense)
                @php $totalExp += $expense->amount; @endphp
                <tr>
                    <td>{{ \Carbon\Carbon::parse($expense->expense_date)->format('d M, Y') }}</td>
                    <td style="text-transform: uppercase">{{ $expense->category }}</td>
                    <td style="text-transform: uppercase">{{ $expense->payment_method }}</td>
                    <td class="text-right">{{ number_format($expense->amount, 2) }}</td>
                    <td>{{ $expense->creator->name ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No expenses recorded.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="font-bold">
                <td colspan="3" class="text-right">Total Expenses:</td>
                <td class="text-right">{{ number_format($totalExp, 2) }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div class="section-title">Office Transfers History</div>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>From Account</th>
                <th>To Account</th>
                <th class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            @php $totalTrans = 0; @endphp
            @forelse($transfers as $transfer)
                @php $totalTrans += $transfer->amount; @endphp
                <tr>
                    <td>{{ \Carbon\Carbon::parse($transfer->transaction_date)->format('d M, Y') }}</td>
                    <td>{{ $transfer->fromAccount->account_name ?? 'N/A' }}</td>
                    <td>{{ $transfer->toAccount->account_name ?? 'N/A' }}</td>
                    <td class="text-right">{{ number_format($transfer->amount, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">No transfers recorded.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="font-bold">
                <td colspan="3" class="text-right">Total Transfers:</td>
                <td class="text-right">{{ number_format($totalTrans, 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <div style="margin-top: 40px; border-top: 2px solid #2196F3; padding-top: 10px;">
        <h3 class="text-right">Net Financial Movement: {{ number_format($totalInc - $totalExp, 2) }}</h3>
        {{-- <p class="text-right" style="font-size: 10px; color: #777;">(Income - Expenses)</p> --}}
    </div>

    <div class="footer">
        Generated on {{ date('d M, Y H:i A') }} | Page 1
    </div>
</body>

</html>
