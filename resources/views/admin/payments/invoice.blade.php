<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Payment Invoice</title>
    <style>
        @page {
            margin: 0;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: sans-serif;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .pdf-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 210mm;
            height: 297mm;
            z-index: 0;
            background-position: top left;
            background-repeat: no-repeat;
            background-size: 210mm 297mm;
        }

        .content {
            position: relative;
            z-index: 1;
            width: 100%;
            padding: 70px 35px 0 35px;
            box-sizing: border-box;
        }

        .invoice-heading {
            margin-top: 100px;
        }

        .info-box {
            padding: 15px;
        }

        .info-box th {
            text-align: left;
            font-size: 18px;
            color: #263a79;
        }

        .info-box td {
            font-size: 13px;
            line-height: 1.6;
        }

        .invoice-meta {
            text-align: right;
            vertical-align: top;
        }

        .invoice-meta p {
            font-size: 14px;
            margin: 0 0 10px 0;
        }

        .invoice-badge {
            display: inline-block;
            background-color: #263a79;
            color: white;
            padding: 10px 12px;
            line-height: 1.2;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .items-table {
            margin-top: 30px;
            border: 1px solid #263a79;
        }

        .items-table th {
            color: #fff;
            padding: 12px 10px;
            font-weight: normal;
            text-align: center;
        }

        .items-table th:nth-child(odd) {
            background-color: #263a79;
        }

        .items-table th:nth-child(even) {
            background-color: #c09f5a;
        }

        .items-table td {
            padding: 10px;
            border: 1px solid #263a79;
            text-align: center;
            vertical-align: top;
        }

        .items-table .text-left {
            text-align: left;
        }

        .items-table .text-right {
            text-align: right;
        }

         .summary-row td {
            padding: 8px 10px;
            border: 1px solid #263a79;
        }

        /*.summary-row td:first-child {
            text-align: right !important;
            font-weight: bold;
        }

        .summary-row td:nth-child(2),
        .summary-row td:nth-child(3),
        .summary-row td:nth-child(4) {
            text-align: right !important;
        }

        .summary-row td:last-child {
            text-align: right !important;
        } */

        .table-shade {
            background-color: #eaecf2;
        }

        .payment-details {
            margin-top: 10px;
            border: 1px solid #263a79;
        }

        .payment-details th {
            width: 22%;
            background-color: #263a79;
            color: #fff;
            padding: 10px;
            font-weight: normal;
            text-align: left;
        }

        .payment-details td {
            padding: 10px;
            color: #322014;
        }
    </style>
</head>
@php
    $bgPath = public_path('assets/images/Invoice_Insaf.jpeg');
    $bgSrc = file_exists($bgPath) ? 'file:///' . str_replace('\\', '/', $bgPath) : null;
    $invoice = $payment->invoice;
    $invoiceTotal = $invoice ? $invoice->total_amount : $payment->amount;
    $invoiceNo = $invoice?->invoice_number ?? ($payment->receipt_number ?: '#' . $payment->id);
@endphp

<body>
    @if ($bgSrc)
        <div class="pdf-bg" style="background-image: url('{{ $bgSrc }}');"></div>
    @endif

    <div class="content">
        <table class="invoice-heading">
            <tr>
                <td style="width: 50%; vertical-align: top;">
                    <table class="info-box">
                        <tr>
                            <th>Invoice To,</th>
                        </tr>
                        <tr>
                            <td>{{ $payment->student->first_name }} {{ $payment->student->last_name }}</td>
                        </tr>
                        <tr>
                            <td>{{ $payment->student->phone }}</td>
                        </tr>
                    </table>
                </td>
                <td style="width: 50%;" class="invoice-meta">
                    <p><span class="invoice-badge">Invoice No: {{ $invoiceNo }}</span></p>
                    <p><strong>Receipt No:</strong> {{ $payment->receipt_number }}</p>
                    <p><strong>Date:</strong> {{ optional($payment->payment_date)->format('Y-m-d') ?? $payment->created_at->format('Y-m-d') }}</p>
                </td>
            </tr>
        </table>

        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 8%;">SL NO.</th>
                    <th style="width: 42%;" class="text-left">PURPOSE</th>
                    <th style="width: 15%;">FEE</th>
                    <th style="width: 10%;">QTY</th>
                    <th style="width: 15%;">TOTAL</th>
                    <th style="width: 10%;">CURRENCY</th>
                </tr>
            </thead>
            <tbody>
                @if ($invoice && $invoice->items->count())
                    @foreach ($invoice->items as $item)
                        <tr>
                            <td class="table-shade">{{ $loop->index + 1 }}</td>
                            <td class="text-left">{{ $item->description }}</td>
                            <td class="table-shade text-right">{{ number_format($item->unit_price, 2) }}</td>
                            <td>{{ $item->quantity ?? '-' }}</td>
                            <td class="table-shade text-right">{{ number_format($item->total, 2) }}</td>
                            <td>BDT</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="table-shade">1</td>
                        <td class="text-left">{{ $payment->notes ?: ucwords($payment->payment_type) . ' payment' }}</td>
                        <td class="table-shade text-right">{{ number_format($payment->amount, 2) }}</td>
                        <td>1</td>
                        <td class="table-shade text-right">{{ number_format($payment->amount, 2) }}</td>
                        <td>BDT</td>
                    </tr>
                @endif

                <tr class="summary-row">
                    <td colspan="4" class="summary-label" style="text-align: right; font-weight: bold;">SUB TOTAL:</td>
                    <td class="table-shade text-right">{{ number_format($invoiceTotal, 2) }}</td>
                    <td>BDT</td>
                </tr>
                <tr class="summary-row">
                    <td colspan="4" class="summary-label" style="text-align: right; font-weight: bold;">PAID THIS RECEIPT:</td>
                    <td class="table-shade text-right">{{ number_format($payment->amount, 2) }}</td>
                    <td>BDT</td>
                </tr>
                <tr class="summary-row">
                    <td colspan="4" class="summary-label" style="text-align: right; font-weight: bold;">TOTAL PAID:</td>
                    <td class="table-shade text-right">{{ number_format($totalPaid, 2) }}</td>
                    <td>BDT</td>
                </tr>
                <tr class="summary-row">
                    <td colspan="4" class="summary-label" style="text-align: right; font-weight: bold;">TOTAL DUE:</td>
                    <td class="table-shade text-right">{{ number_format($remainingBalance, 2) }}</td>
                    <td>BDT</td>
                </tr>
            </tbody>
        </table>

    </div>

    <div class="content">
        <table class="payment-details">
            <tr>
                <th>PAYMENT TYPE</th>
                <td>{{ ucwords($payment->payment_type) }} Payment @if ($payment->account) | {{ str_replace('INSAF - ', '', $payment->account->account_name) }}@if ($payment->account->account_number && $payment->account->account_number !== '-') ({{ $payment->account->account_number }})@endif @endif
                </td>
            </tr>
            <tr>
                <th>AMOUNT</th>
                <td><strong>BDT {{ number_format($payment->amount, 2) }}</strong></td>
            </tr>
            <tr>
                <th>COLLECTED BY</th>
                <td>{{ $payment->collector->name ?? 'N/A' }}</td>
            </tr>
            {{-- @if ($payment->account)
            <tr>
                <th>PAYMENT METHOD</th>
                <td>{{ ucwords(str_replace('_', ' ', $payment->account->account_type)) }} — {{ $payment->account->account_name }}@if($payment->account->account_number) ({{ $payment->account->account_number }})@endif</td>
            </tr>
            @endif --}}
        </table>
    </div>

    {{-- <div class="content" style="padding-top: 20px;">
        <table style="width: 100%;">
            <tr>
                <td style="width: 50%; vertical-align: bottom;">
                    <div style="text-align: center;">
                        @php
                            $signaturePath = public_path('assets/images/signature.png');
                            $signatureSrc = file_exists($signaturePath) ? 'file:///' . str_replace('\\', '/', $signaturePath) : null;
                        @endphp
                        @if ($signatureSrc)
                            <img src="{{ $signatureSrc }}" alt="Signature" style="height: 50px; margin-bottom: 5px;" />
                        @else
                            <div style="height: 50px; margin-bottom: 5px;"></div>
                        @endif
                        <div style="border-top: 1px solid #263a79; width: 200px; margin: 0 auto; padding-top: 5px;">
                            <strong>Accountant Signature</strong>
                        </div>
                    </div>
                </td>
                <td style="width: 50%; vertical-align: bottom;">
                    <div style="text-align: center;">
                        <div style="height: 55px;"></div>
                        <div style="border-top: 1px solid #263a79; width: 200px; margin: 0 auto; padding-top: 5px;">
                            <strong>Authorized Signature</strong>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div> --}}
</body>

</html>
