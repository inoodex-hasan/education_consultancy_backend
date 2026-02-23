<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Expense Details - #{{ $expense->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .section {
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #fff;
            background-color: #333;
            padding: 8px 12px;
            margin-bottom: 10px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #ddd;
        }

        .detail-label {
            font-weight: bold;
            width: 40%;
        }

        .detail-value {
            width: 60%;
            text-align: right;
        }

        .amount {
            font-size: 16px;
            font-weight: bold;
            color: #e91e63;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Expense Details</h1>
        <p>Expense ID: #{{ $expense->id }}</p>
    </div>

    <div class="section">
        <div class="section-title">Basic Information</div>
        <div class="detail-row">
            <div class="detail-label">Description:</div>
            <div class="detail-value">{{ $expense->description }}</div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Expense Date:</div>
            <div class="detail-value">{{ $expense->expense_date->format('M d, Y') }}</div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Category:</div>
            <div class="detail-value">{{ $expense->category ?: 'General' }}</div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Payment Details</div>
        <div class="detail-row">
            <div class="detail-label">Amount:</div>
            <div class="detail-value amount">{{ number_format($expense->amount, 2) }}</div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Payment Method:</div>
            <div class="detail-value">{{ $expense->payment_method ?: '-' }}</div>
        </div>
        @if ($expense->office_account)
            <div class="detail-row">
                <div class="detail-label">Account:</div>
                <div class="detail-value">{{ $expense->office_account->account_name }}</div>
            </div>
        @endif
    </div>

    @if ($expense->notes)
        <div class="section">
            <div class="section-title">Notes</div>
            <div class="detail-row">
                <div class="detail-value">{{ $expense->notes }}</div>
            </div>
        </div>
    @endif

    <div class="section">
        <div class="section-title">Record Information</div>
        <div class="detail-row">
            <div class="detail-label">Recorded By:</div>
            <div class="detail-value">{{ $expense->creator->name ?? 'System' }}</div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Created Date:</div>
            <div class="detail-value">{{ $expense->created_at->format('M d, Y H:i A') }}</div>
        </div>
        @if ($expense->updated_at !== $expense->created_at)
            <div class="detail-row">
                <div class="detail-label">Last Updated:</div>
                <div class="detail-value">{{ $expense->updated_at->format('M d, Y H:i A') }}</div>
            </div>
        @endif
    </div>

    <div class="footer">
        <p>This is a computer-generated document. Generated on {{ now()->format('M d, Y H:i A') }}</p>
    </div>
</body>

</html>
