<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            background: #f8fdf9;
            width: 297mm; height: 210mm;
            display: flex; align-items: center; justify-content: center;
        }
        .cert {
            width: 270mm; height: 190mm;
            border: 8px solid #16a34a;
            border-radius: 12px;
            padding: 30px 50px;
            text-align: center;
            background: white;
            position: relative;
        }
        .logo { font-size: 28px; font-weight: bold; color: #16a34a; margin-bottom: 8px; }
        .subtitle { font-size: 13px; color: #6b7280; margin-bottom: 30px; }
        .title { font-size: 36px; color: #111827; font-weight: bold; margin-bottom: 6px; }
        .presented { font-size: 14px; color: #6b7280; margin-bottom: 10px; }
        .donor-name { font-size: 32px; color: #16a34a; font-weight: bold; margin-bottom: 20px; border-bottom: 2px solid #dcfce7; padding-bottom: 10px; }
        .amount-text { font-size: 16px; color: #374151; margin-bottom: 8px; }
        .amount { font-size: 28px; font-weight: bold; color: #111827; margin-bottom: 6px; }
        .campaign { font-size: 14px; color: #6b7280; margin-bottom: 30px; }
        .footer { display: flex; justify-content: space-between; align-items: flex-end; margin-top: 20px; }
        .cert-num { font-size: 11px; color: #9ca3af; }
        .date { font-size: 11px; color: #9ca3af; }
        .qr-placeholder { font-size: 10px; color: #9ca3af; text-align: right; }
    </style>
</head>
<body>
<div class="cert">
    <div class="logo">CharityHub</div>
    <div class="subtitle">Certificate of Donation</div>
    <div class="title">Certificate of Appreciation</div>
    <div class="presented">This certificate is proudly presented to</div>
    <div class="donor-name">{{ $donation->donor_name }}</div>
    <div class="amount-text">for a generous donation of</div>
    <div class="amount">{{ number_format($donation->amount) }} {{ $donation->currency }}</div>
    <div class="campaign">towards <em>{{ $donation->campaign->title }}</em></div>
    <div class="footer">
        <div>
            <div class="cert-num">Certificate No: {{ $certificate->certificate_number }}</div>
            <div class="date">Issued: {{ $certificate->issued_at->format('d M Y') }}</div>
        </div>
        <div class="qr-placeholder">
            Verify at:<br>{{ $verifyUrl }}
        </div>
    </div>
</div>
</body>
</html>