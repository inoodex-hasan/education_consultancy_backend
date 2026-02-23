<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #000;
            font-size: 12px;
            line-height: 1.3;
        }

        .container {
            width: 100%;
        }

        .header {
            width: 100%;
            margin-bottom: 20px;
            overflow: hidden;
        }

        .company-info {
            float: left;
            width: 45%;
        }

        .invoice-info {
            float: right;
            width: 45%;
            text-align: right;
        }

        .company-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .logo {
            display: inline-block;
            width: 20px;
            height: 20px;
            background: #000;
            color: #fff;
            border-radius: 50%;
            text-align: center;
            line-height: 20px;
            margin-right: 5px;
        }

        .invoice-title {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin: 10px 0;
            clear: both;
        }

        .customer-info {
            width: 100%;
            margin-bottom: 20px;
            overflow: hidden;
        }

        .customer-left {
            float: left;
            width: 45%;
        }

        .customer-right {
            float: right;
            width: 45%;
            text-align: right;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            page-break-inside: avoid;
        }

        .items-table th,
        .items-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        .items-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .footer-section {
            width: 100%;
            margin-bottom: 20px;
            overflow: hidden;
        }

        .terms {
            float: left;
            width: 50%;
        }

        .totals {
            float: right;
            width: 45%;
        }

        .totals table {
            width: 100%;
            border-collapse: collapse;
        }

        .totals td {
            padding: 5px 0;
            text-align: right;
        }

        .totals td:first-child {
            text-align: left;
            padding-right: 10px;
        }

        .in-words {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
        }

        .signatures {
            width: 100%;
            margin-bottom: 20px;
            overflow: hidden;
        }

        .signature {
            float: left;
            width: 45%;
            border-top: 1px solid #000;
            text-align: center;
            padding-top: 40px;
            margin-right: 5%;
        }

        .signature:last-child {
            margin-right: 0;
            float: right;
        }

        .page-number {
            text-align: right;
            font-size: 10px;
            margin-top: 20px;
        }

        /* Clearfix */
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header clearfix">
            <div class="company-info">
                <div class="company-name">
                    <h1 class="company-name">{{ $settings['app_name'] ?? 'Educational Consultancy' }}</h1>
                </div>
                <p>
                    {{ $settings['address'] ?? '' }}<br>
                    Phone: {{ $settings['contact_phone'] ?? '' }}<br>
                    Email: {{ $settings['contact_email'] ?? '' }}
                </p>
            </div>
            <div class="invoice-info">
                Date: {{ date('Y-m-d') }}<br>
            </div>
        </div>

        <div class="invoice-title">INVOICE</div>

        <div class="customer-info clearfix">
            <div class="customer-left">
                <strong>Name:</strong> {{ $payment->student->first_name }}
                {{ $payment->student->last_name ?? 'N/A' }}<br>
                <strong>Phone:</strong> {{ $payment->student->phone ?? 'N/A' }}<br>
                <strong>Address:</strong> {{ $payment->student->address ?? 'N/A' }}
            </div>
            <div class="customer-right">
                <strong>Invoice No:</strong>{{ $payment->id ?? 'N/A' }}<br>
                <strong>Invoice Date:</strong> {{ $payment->created_at->format('Y-m-d') ?? date('Y-m-d') }}
            </div>
        </div>

        <table class="items-table">
            <thead>
                <tr>
                    <th>Sl</th>
                    <th class="label">Application ID</th>
                    {{-- <th>Qty</th> --}}
                    <th>Amount</th>
                    {{-- <th>Total Price</th> --}}
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>#</td>

                    <td class="value">{{ $payment->application->application_id ?? 'N/A' }}</td>
                    {{-- <td>{{ $payment->qty ?? 'N/A' }}</td> --}}
                    <td>{{ $payment->amount ?? 'N/A' }}</td>
                    {{-- <td>{{ $payment->bill ?? 'N/A' }}</td> --}}
                </tr>
            </tbody>
        </table>

        <div class="section">
            <div class="section-title">Payment info</div>
            <table class="details-table">
                <tr>
                    <td class="label">Payment Type:</td>
                    <td class="value" style="text-transform: capitalize;">{{ $payment->payment_type }} Payment</td>
                </tr>
                <tr>
                    <td class="label">Collected By:</td>
                    <td class="value">{{ $payment->collector->name ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>

        <div class="amount-section">
            <span class="total-label">Amount Paid:</span>
            <span class="total-amount">BDT {{ number_format($payment->amount, 2) }}</span>
        </div>

        {{-- <div class="footer-section clearfix">
            <div class="terms">
                <strong>Terms & Conditions</strong><br>
                Products can be returned within 7 days in their
                original, unopened condition. Refunds or exchanges
                are offered, but perishable goods cannot be
                returned. Contact us at 01904400205 with a valid
                receipt for assistance.
            </div>
            <div class="totals">
                <table>
                    <tr>
                        <td>Sub Total:</td>
                        <td>9,600.00 Tk</td>
                    </tr>
                    <tr>
                        <td>Delivery Fee:</td>
                        <td>200.00 Tk</td>
                    </tr>
                    <tr>
                        <td>Total Price:</td>
                        <td>9,800.00 Tk</td>
                    </tr>
                    <tr>
                        <td>Received:</td>
                        <td>0.00 Tk</td>
                    </tr>
                    <tr>
                        <td>Total Due:</td>
                        <td>9,800.00 Tk</td>
                    </tr>
                    <tr>
                        <td>Previous Receivable:</td>
                        <td>0.00 Tk</td>
                    </tr>
                    <tr>
                        <td>Current Receivable:</td>
                        <td>9,800.00 Tk</td>
                    </tr>
                </table>
            </div>
        </div> --}}

        {{-- <div class="in-words">
            <strong>In Words:</strong> Nine Thousand Eight Hundred Taka Only
        </div> --}}

        <div class="signatures clearfix">
            <div class="signature">Customer Signature</div>
            <div class="signature">Authorized Signature</div>
        </div>

        <div class="page-number">
            Page 1/1
        </div>
    </div>
</body>

</html>
