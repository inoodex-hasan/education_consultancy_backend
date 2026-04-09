<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Application Details - {{ $application->application_id }}</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 14px;
            color: #333;
            line-height: 1.6;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #4361ee;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            color: #4361ee;
        }

        .section {
            margin-bottom: 30px;
        }

        .section-title {
            background-color: #f8f9fa;
            padding: 8px 12px;
            font-weight: bold;
            border-left: 4px solid #4361ee;
            margin-bottom: 15px;
            font-size: 16px;
        }

        .info-grid {
            width: 100%;
            border-collapse: collapse;
        }

        .info-grid td {
            padding: 8px;
            vertical-align: top;
        }

        .label {
            width: 30%;
            font-weight: bold;
            color: #666;
        }

        .value {
            width: 70%;
        }

        .footer {
            margin-top: 50px;
            font-size: 12px;
            text-align: center;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 10px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 4px;
            background-color: #4361ee;
            color: white;
            text-transform: capitalize;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Application Details</h1>
        <p>ID: <strong>{{ $application->application_id }}</strong></p>
    </div>

    <div class="section">
        <div class="section-title">Student Information</div>
        <table class="info-grid">
            <tr>
                <td class="label">Full Name:</td>
                <td class="value">{{ $application->student->first_name }} {{ $application->student->last_name }}</td>
            </tr>
            <tr>
                <td class="label">Email:</td>
                <td class="value">{{ $application->student->email }}</td>
            </tr>
            <tr>
                <td class="label">Phone:</td>
                <td class="value">{{ $application->student->phone }}</td>
            </tr>
            <tr>
                <td class="label">Passport Number:</td>
                <td class="value">{{ $application->student->passport_number }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Academic Details</div>
        <table class="info-grid">
            <tr>
                <td class="label">Country:</td>
                <td class="value">{{ $application->university->country->name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">University:</td>
                <td class="value">{{ $application->university->name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Course:</td>
                <td class="value">{{ $application->course->name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Intake:</td>
                <td class="value">{{ $application->intake->intake_name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Tuition Fee:</td>
                <td class="value">{{ number_format($application->tuition_fee, 2) }}</td>
            </tr>
            <tr>
                <td class="label">Total Fee:</td>
                <td class="value">{{ number_format($application->total_fee, 2) }}
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Application Status</div>
        <table class="info-grid">
            <tr>
                <td class="label">Current Status:</td>
                <td class="value"><span class="status-badge">{{ str_replace('_', ' ', $application->status) }}</span>
                </td>
            </tr>
            @if($application->notes)
                <tr>
                    <td class="label">Notes:</td>
                    <td class="value">{{ $application->notes }}</td>
                </tr>
            @endif
            <tr>
                <td class="label">Created At:</td>
                <td class="value">{{ $application->created_at->format('M d, Y H:i A') }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        This is an auto-generated document. {{ date('M d, Y') }}
    </div>
</body>

</html>