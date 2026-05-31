<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipt</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .timestamp { font-size: 12px; color: #888; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background: #f5f5f5; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Pharmacy Receipt</h2>
        <div class="timestamp">{{ $timestamp }}</div>
    </div>
    <div>
        <strong>Transaction ID:</strong> {{ $sale->id }}<br>
        <strong>Pharmacist ID:</strong> {{ $sale->pharmacist_id ?? 'N/A' }}<br>
        <strong>Date:</strong> {{ $sale->created_at }}<br>
        <strong>Customer:</strong> {{ $sale->customer_name ?? 'Walk-in' }}<br>
    </div>
    <h4>Items</h4>
    <table>
        <thead>
            <tr><th>Medicine</th><th>Qty</th><th>Unit Price</th><th>Total</th><th>Dosage</th></tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $sale->medicine_name ?? '-' }}</td>
                <td>{{ $sale->quantity }}</td>
                <td>{{ number_format((float) $sale->unit_price, 2) }}</td>
                <td>{{ number_format((float) $sale->total_amount, 2) }}</td>
                <td>{{ $sale->dosage_instructions ?? 'Take as directed by pharmacist.' }}</td>
            </tr>
        </tbody>
    </table>
    <div>
        <strong>Subtotal:</strong> {{ number_format((float) ($sale->subtotal ?? $sale->total_amount), 2) }}<br>
        <strong>Discount:</strong> {{ number_format((float) ($sale->discount ?? 0), 2) }}<br>
        <strong>Tax:</strong> {{ number_format((float) ($sale->tax ?? 0), 2) }}<br>
        <strong>Total:</strong> {{ number_format((float) $sale->total_amount, 2) }}<br>
        <strong>Payment Method:</strong> {{ $sale->payment_method ?? 'Cash' }}<br>
    </div>
    <div class="timestamp">Receipt is read-only.</div>
</body>
</html>
