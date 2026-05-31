<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Pharmacy Receipt</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #2a1d49;
            font-size: 12px;
        }
        .header {
            border-bottom: 2px solid #7c3aed;
            margin-bottom: 16px;
            padding-bottom: 10px;
        }
        .title {
            font-size: 20px;
            font-weight: bold;
            color: #5b21b6;
            margin: 0;
        }
        .meta {
            margin-top: 6px;
            color: #4b3f72;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 14px;
        }
        th, td {
            border: 1px solid #d5c4ff;
            padding: 8px;
            text-align: left;
        }
        th {
            background: #f5efff;
            color: #4c1d95;
        }
        .amount {
            text-align: right;
        }
        .totals {
            width: 45%;
            margin-left: auto;
            margin-top: 16px;
        }
        .totals td {
            border: none;
            padding: 4px 0;
        }
        .totals .label {
            font-weight: bold;
            color: #4c1d95;
        }
        .totals .grand {
            border-top: 1px solid #c4b5fd;
            font-size: 14px;
        }
        .footer {
            margin-top: 28px;
            font-size: 11px;
            color: #5b4c8f;
            border-top: 1px dashed #c4b5fd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <p class="title">PharmaCare Invoice</p>
        <p class="meta"><strong>Invoice #:</strong> {{ $transaction->id }}</p>
        <p class="meta"><strong>Date:</strong> {{ $transaction->created_at?->format('M d, Y h:i A') }}</p>
        <p class="meta"><strong>Payment Method:</strong> {{ $transaction->payment_method }}</p>
        <p class="meta"><strong>Transaction Type:</strong> {{ ucfirst($transaction->transaction_type) }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Medicine</th>
                <th class="amount">Qty</th>
                <th class="amount">Unit Price</th>
                <th class="amount">Line Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaction->items as $item)
                <tr>
                    <td>{{ $item->medicine_name }}</td>
                    <td class="amount">{{ $item->quantity }}</td>
                    <td class="amount">{{ number_format((float) $item->unit_price, 2) }}</td>
                    <td class="amount">{{ number_format((float) $item->line_total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals">
        <tr>
            <td class="label">Subtotal</td>
            <td class="amount">{{ number_format($subtotal, 2) }}</td>
        </tr>
        <tr>
            <td class="label">Tax</td>
            <td class="amount">{{ number_format($tax, 2) }}</td>
        </tr>
        <tr class="grand">
            <td class="label">Grand Total</td>
            <td class="amount"><strong>{{ number_format($total, 2) }}</strong></td>
        </tr>
    </table>

    <div class="footer">
        <p><strong>Pharmacist:</strong> {{ $pharmacistName }}</p>
        <p>Thank you for trusting PharmaCare. Keep this invoice for your records.</p>
    </div>
</body>
</html>
