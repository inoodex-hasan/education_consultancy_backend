<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice</title>
    <style>
        /* mPDF is sensitive to global resets; keep it simple */
        body {
            margin: 0;
            padding: 0;
            font-family: sans-serif;
            color: #47389D;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        .text-blue { color: #47389D; }
        .text-gold { color: #C2A56D; }
        .bg-blue { background-color: #47389D; }
        .bg-gold { background-color: #C2A56D; }
        
        /* Watermark style for mPDF */
        .watermark {
            position: absolute;
            top: 30%;
            left: 10%;
            width: 80%;
            opacity: 0.1;
            z-index: -1000;
        }
    </style>
</head>
<body>
    @php
        // Use base64 for mPDF to avoid path resolution issues during rendering
        $logoPath = public_path('assets/images/logo-insaf.png');
        $logoSrc = file_exists($logoPath)
            ? 'data:image/' . pathinfo($logoPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($logoPath))
            : null;

        $qrPath = public_path('assets/images/invoice-qr-code.png');
        $qrSrc = file_exists($qrPath)
            ? 'data:image/' . pathinfo($qrPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($qrPath))
            : null;
    @endphp

    @if ($logoSrc)
        <div class="watermark">
            <img src="{{ $logoSrc }}" style="width: 100%; opacity: 0.1;">
        </div>
    @endif

    <div style="text-align: center; margin-bottom: 10px;">
        @if ($logoSrc)
            <img src="{{ $logoSrc }}" style="width: 180px;">
        @endif
        <h1 style="font-size: 14px; font-weight: normal; margin-top: 5px;">Study Abroad, Grow Globally</h1>
    </div>

    <table style="width: 100%; margin-top: 10px;" cellpadding="0" cellspacing="0">
        <tr>
            <td class="bg-blue" style="height: 8px; width: 50%;"></td>
            <td class="bg-gold" style="height: 12px; width: 50%;"></td>
        </tr>
    </table>

    <table style="margin-top: 25px;">
        <tr>
            <td style="width: 55%; vertical-align: top;">
                <table style="border: 1px solid #47389D;">
                    <tr>
                        <th colspan="2" style="font-size: 24px; padding: 10px; text-align: left; border-bottom: 1px solid #47389D;">Invoice to:</th>
                    </tr>
                    <tr>
                        <td style="padding: 8px; border: 1px solid #47389D; width: 30%;">Name:</td>
                        <td style="padding: 8px; border: 1px solid #47389D;">{{$application->student->first_name}} {{$application->student->last_name}}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px; border: 1px solid #47389D;">Phone:</td>
                        <td style="padding: 8px; border: 1px solid #47389D;">{{$application->student->phone}}</td>
                    </tr>
                </table>
            </td>
            <td style="width: 45%; text-align: right; vertical-align: top;">
    <h1 style="font-size: 40px; margin: 0;">Invoice</h1>

    <div style="text-align: left; margin-top: 10px;">
        <p style="font-size: 16px; margin: 5px 0;">Date:{{ $application->created_at->format('Y-m-d') }} </p>
        <p style="font-size: 16px; margin: 0;">Invoice No: {{ $application->id }} </p>
    </div>
</td>
            <!-- <td style="width: 45%; text-align: right; vertical-align: top;">
                <h1 style="font-size: 40px; margin: 0; ">Invoice</h1>
                <p style="font-size: 16px; margin: 5px 0; text-align: left">Date: </p>
                <p style="font-size: 16px; margin: 0; text-align: left">Invoice No: </p>
            </td> -->
        </tr>
    </table>

    <table style="margin-top: 25px; border: 1px solid #47389D;">
    <thead>
    <tr style="background-color: #47389D;">
        <th style="padding: 10px; font-weight: 400; color: #ffffff; border: 1pt solid #47389D;">Sr No.</th>
        <th style="padding: 10px; font-weight: 400; color: #ffffff; border: 1pt solid #47389D;">Purpose:</th>
        <th style="padding: 10px; font-weight: 400; color: #ffffff; border: 1pt solid #47389D;">Fee</th>
        <th style="padding: 10px; font-weight: 400; color: #ffffff; border: 1pt solid #47389D;">Qty</th>
        <th style="padding: 10px; font-weight: 400; color: #ffffff; border: 1pt solid #47389D;">Total</th>
        <th style="padding: 10px; font-weight: 400; color: #ffffff; border: 1pt solid #47389D;">Currency</th>
    </tr>
</thead>
        <tbody>
            <tr style="text-align: center;">
                <td style="padding: 10px; border: 1px solid #47389D;">1</td>
                <td style="padding: 10px; border: 1px solid #47389D;">{{$application->purpose}}</td>
                <td style="padding: 10px; border: 1px solid #47389D;">{{$application->fee}}</td>
                <td style="padding: 10px; border: 1px solid #47389D;">1</td>
                <td style="padding: 10px; border: 1px solid #47389D;">{{$application->total}}</td>
                <td style="padding: 10px; border: 1px solid #47389D;">BDT</td>
            </tr>
            <tr>
                <td colspan="3" rowspan="5" style="border: 1px solid #47389D; vertical-align: top; padding: 15px;">
                    <h3 style="font-size: 18px; margin-bottom: 8px;">Payment Method:</h3>
                    <p style="margin: 2px 0;">Account: 2076708660001</p>
                    <p style="margin: 2px 0;">Account Name: INSAF IMMIGRATION</p>
                    <p style="margin: 2px 0;">Bank Name : BRAC Bank PLC</p>
                    <p style="margin: 2px 0;">Branch Name : PANTHAPATH BRANCH</p>
                    <p style="margin: 2px 0;">Routing Number : 060263629</p>
                </td>
                <td style="padding: 8px; border: 1px solid #47389D;">Sub Total</td>
                <td style="padding: 8px; border: 1px solid #47389D;">{{$application->total}}</td>
                <td style="padding: 8px; border: 1px solid #47389D;">BDT</td>
            </tr>
            <tr>
                <td style="padding: 8px; border: 1px solid #47389D;">Paid:</td>
                <td style="padding: 8px; border: 1px solid #47389D;">{{$application->paid}}</td>
                <td style="padding: 8px; border: 1px solid #47389D;">BDT</td>
            </tr>
            <tr>
                <td style="padding: 8px; border: 1px solid #47389D;">Due:</td>
                <td style="padding: 8px; border: 1px solid #47389D;">{{$application->due}}</td>
                <td style="padding: 8px; border: 1px solid #47389D;">BDT</td>
            </tr>
            <tr>
                <td style="padding: 8px; border: 1px solid #47389D;">Total Paid:</td>
                <td style="padding: 8px; border: 1px solid #47389D;">{{$application->paid}}</td>
                <td style="padding: 8px; border: 1px solid #47389D;">BDT</td>
            </tr>
            <tr>
                <td style="padding: 8px; border: 1px solid #47389D;">Total Due:</td>
                <td style="padding: 8px; border: 1px solid #47389D;">{{$application->due}}</td>
                <td style="padding: 8px; border: 1px solid #47389D;">BDT</td>
            </tr>
        </tbody>
    </table>

    <h2 class="text-gold" style="font-size: 18px; margin-top: 20px;">Note:{{$application->note}}</h2>

    <table style="margin-top: 80px; width: 100%;">
        <tr>
            <td style="width: 40%; border-top: 1px solid #47389D; padding-top: 10px; text-align: center;">Applicant's Signature</td>
            <td style="width: 20%;"></td>
            <td style="width: 40%; border-top: 1px solid #47389D; padding-top: 10px; text-align: center;">Accountant Signature</td>
        </tr>
    </table>

    <div style="margin-top: 50px;">
        <table style="width: 100%;">
            <tr>
                <td style="width: 15%; vertical-align: bottom;">
                    @if ($qrSrc)
                        <img src="{{ $qrSrc }}" style="width: 70px; height: 70px;">
                    @endif
                </td>
                <td style="width: 85%;">
                    <table style="width: 100%; font-size: 10px;">
                        <tr>
                            <td>
                                <strong style="font-size: 11px;">Dhaka Office</strong><br>
                                Haq Tower (Opposite of BRB Hospital)<br>
                                Floor 6, Panthapath, Dhaka-1209
                            </td>
                            <td>
                                <strong style="font-size: 11px;">Chattogram Office</strong><br>
                                Ridima Tower (Shahjalal Bank Building)<br>
                                Level-4, Chawkbazar, Chattogram
                            </td>
                            <td style="text-align: right;">
                                <strong style="font-size: 11px;">+88 01880-942457</strong><br>
                                insafimmigration@gmail.com
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table style="width: 100%; margin-top: 10px;" cellpadding="0" cellspacing="0">
            <tr>
                <td class="bg-blue" style="height: 8px; width: 50%;"></td>
                <td class="bg-gold" style="height: 30px; width: 50%;"></td>
            </tr>
        </table>
    </div>

</body>
</html>