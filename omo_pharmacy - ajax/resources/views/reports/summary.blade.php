<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Summary Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .timestamp {
            font-size: 12px;
            color: #888;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        th {
            background: #f5f5f5;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>Pharmacy Summary Report ({{ ucfirst($type) }})</h2>
        <div class="timestamp">Generated: {{ $timestamp }}</div>
        <div class="timestamp">
            Coverage: {{ data_get($data, 'range.start', 'N/A') }} to {{ data_get($data, 'range.end', 'N/A') }}
        </div>
    </div>

    <h4>Summary</h4>
    <table>
        <tbody>
            <tr>
                <th>Transactions</th>
                <td>{{ data_get($data, 'totals.transactions', 0) }}</td>
            </tr>
            <tr>
                <th>Total Revenue</th>
                <td>{{ number_format((float) data_get($data, 'totals.revenue', 0), 2) }}</td>
            </tr>
            <tr>
                <th>Estimated Profit</th>
                <td>{{ number_format((float) data_get($data, 'totals.estimated_profit', 0), 2) }}</td>
            </tr>
            <tr>
                <th>Profit Margin</th>
                <td>{{ number_format((float) data_get($data, 'totals.profit_margin', 0), 2) }}%</td>
            </tr>
        </tbody>
    </table>

    <!-- Example: Sales Table -->
    <h4>Sales</h4>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Transaction ID</th>
                <th>Medicine</th>
                <th>Customer</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data['sales'] ?? [] as $sale)
                <tr>
                    <td>{{ $sale['date'] }}</td>
                    <td>{{ $sale['id'] }}</td>
                    <td>{{ $sale['medicine_name'] }}</td>
                    <td>{{ $sale['customer_name'] }}</td>
                    <td>{{ $sale['total'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <!-- Example: Low Stock Table -->
    <h4>Low Stock Medicines</h4>
    <table>
        <thead>
            <tr>
                <th>Medicine</th>
                <th>Quantity</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data['low_stock'] ?? [] as $med)
                <tr>
                    <td>{{ $med['medicine_name'] }}</td>
                    <td>{{ $med['quantity'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="timestamp">Report is read-only. ID: {{ uniqid('report_') }}</div>
</body>

</html>
