<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Profit/Loss Report - {{ $fromDate }} to {{ $toDate }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            padding: 20px;
            color: #333;
        }

        .header,
        .footer {
            text-align: center;
        }

        .summary-table,
        .product-table,
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        th {
            background-color: #f4f4f4;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        .positive {
            color: #27ae60;
        }

        .negative {
            color: #e74c3c;
        }
    </style>
</head>

<body>

    <div class="header">
        <h2>Shopno</h2>
        <p>123 Business Street, Dhaka<br>Phone: (+880) 456-7890 | Email: info@shopno.com</p>
        <h3>Profit & Loss Report</h3>
        <p>From {{ $fromDate }} to {{ $toDate }}</p>
    </div>

    <h4>Summary</h4>
    <table class="summary-table">
        <tr>
            <th class="text-left">Total Sales</th>
            <td class="text-right">{{ number_format($salesTotal, 2) }}</td>
        </tr>

        <tr>
            <th class="text-left">COGS (Cost of Sold Products)</th>
            <td class="text-right">{{ number_format($cogs, 2) }}</td>
        </tr>
        <tr>
            <th class="text-left">Net {{ $reportType }}</th>
            <td class="text-right {{ $profit >= 0 ? 'positive' : 'negative' }}">{{ number_format($profit, 2) }}</td>
        </tr>
    </table>

    <h4>Sold Products</h4>
    <table class="product-table">
        <thead>
            <tr>
                <th>#</th>
                <th class="text-left">Product</th>
                <th>Qty</th>
                <th>Rate</th>
                <th>Total Cost</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($soldItems as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td class="text-left">{{ $item->product->name ?? 'N/A' }}</td>
                    <td class="text-right">{{ $item->quantity }}</td>
                    <td class="text-right">{{ number_format($item->product->purchase_price, 2) }}</td>
                    <td class="text-right">{{ number_format($item->quantity * $item->product->purchase_price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h4>Invoice Summary</h4>
    <table class="invoice-table">
        <thead>
            <tr>
                <th>#</th>
                <th class="text-left">Invoice ID</th>
                <th class="text-left">Customer</th>
                <th>Date</th>
                <th>Total</th>
                <th>VAT</th>
                <th>Discount</th>
                <th>Payable</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoices as $i => $invoice)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td class="text-left">INV-{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}</td>
                    <td class="text-left">{{ $invoice->customer->name ?? 'N/A' }}</td>
                    <td>{{ \Carbon\Carbon::parse($invoice->created_at)->format('d M Y') }}</td>
                    <td class="text-right">{{ number_format($invoice->total, 2) }}</td>
                    <td class="text-right">{{ number_format($invoice->vat, 2) }}</td>
                    <td class="text-right">{{ number_format($invoice->discount, 2) }}</td>
                    <td class="text-right">{{ number_format($invoice->payable, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Generated on {{ now()->format('d M Y H:i') }} | &copy; {{ now()->year }} Shopno
    </div>

</body>

</html>
