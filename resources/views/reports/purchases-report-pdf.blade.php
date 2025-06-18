<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Purchase Report - {{ request()->FromDate }} to {{ request()->ToDate }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
            margin: 0;
            padding: 15px;
        }

        .report-header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #3498db;
            padding-bottom: 15px;
        }

        .report-title {
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .report-period {
            color: #7f8c8d;
            font-size: 14px;
        }

        .company-info {
            margin-bottom: 20px;
            text-align: center;
            font-size: 11px;
            color: #555;
        }

        .summary-section {
            background-color: #f8f9fa;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 25px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .summary-title {
            color: #2c3e50;
            margin-bottom: 15px;
            font-size: 14px;
            font-weight: 600;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }

        .summary-table tr:last-child {
            font-weight: bold;
            background-color: #e8f4fc;
        }

        .summary-table th,
        .summary-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .summary-table th {
            width: 30%;
            color: #7f8c8d;
        }

        .invoice-section {
            margin-top: 25px;
        }

        .section-title {
            color: #2c3e50;
            margin-bottom: 15px;
            font-size: 14px;
            font-weight: 600;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        .invoice-table th {
            background-color: #3498db;
            color: white;
            padding: 8px;
            text-align: left;
        }

        .invoice-table td {
            padding: 8px;
            border-bottom: 1px solid #eee;
        }

        .invoice-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .invoice-table tr:hover {
            background-color: #f1f9ff;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #95a5a6;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }

        .highlight {
            color: #e74c3c;
            font-weight: bold;
        }

        .positive {
            color: #27ae60;
        }
    </style>
</head>
<body>

<div class="company-info">
    <strong>Shopno</strong><br>
    123 Business Street, Dhaka, Bangladesh<br>
    Phone: (+880) 456-7890 | Email: info@shopno.com
</div>

<div class="report-header">
    <h1 class="report-title">Purchase Report</h1>
    <p class="report-period">From {{ request()->FromDate }} to {{ request()->ToDate }}</p>
</div>

<div class="summary-section">
    <h3 class="summary-title">Financial Summary</h3>
    <table class="summary-table">
        <tr>
            <th>Total Purchase Amount</th>
            <td class="text-right">{{ number_format($total, 2) }}</td>
        </tr>
        <tr>
            <th>Total VAT</th>
            <td class="text-right">{{ number_format($vat, 2) }}</td>
        </tr>
        <tr>
            <th>Total Discount</th>
            <td class="text-right">{{ number_format($discount, 2) }}</td>
        </tr>
        <tr>
            <th>Net Payable Amount</th>
            <td class="text-right highlight">{{ number_format($payable, 2) }}</td>
        </tr>
    </table>
</div>

<div class="invoice-section">
    <h3 class="section-title">Invoice Details</h3>
    <table class="invoice-table">
        <thead>
        <tr>
            <th class="text-center">#</th>
            <th>Purchase ID</th>
            <th>Supplier</th>
            <th>Contact</th>
            <th class="text-center">Date</th>
            <th class="text-right">Total</th>
            <th class="text-right">VAT</th>
            <th class="text-right">Discount</th>
            <th class="text-right">Payable</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($purchases as $index => $purchase)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>INV-{{ str_pad($purchase->id, 5, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $purchase->supplier->name ?? 'N/A' }}</td>
                <td>
                    {{ $purchase->supplier->email ?? 'N/A' }}<br>
                    {{ $purchase->supplier->mobile ?? 'N/A' }}
                </td>
                <td class="text-center">{{ \Carbon\Carbon::parse($purchase->created_at)->format('d M Y') }}</td>
                <td class="text-right">{{ number_format($purchase->total, 2) }}</td>
                <td class="text-right">{{ number_format($purchase->vat, 2) }}</td>
                <td class="text-right">{{ number_format($purchase->discount, 2) }}</td>
                <td class="text-right positive">{{ number_format($purchase->payable, 2) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div class="footer">
    Generated on {{ now()->format('d M Y H:i') }} | &copy; {{ now()->year }} Shopno. All rights reserved.
</div>

</body>
</html>
