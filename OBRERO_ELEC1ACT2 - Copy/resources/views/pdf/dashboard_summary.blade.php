<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard {{ $periodLabel }} Summary Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #1f2937; font-size: 12px; }
        h1 { margin: 0 0 4px; color: #7c3aed; }
        h2 { margin: 20px 0 8px; color: #4c1d95; font-size: 14px; }
        .meta { margin-bottom: 10px; color: #6b7280; }
        .section { margin-top: 14px; }
        .summary, .table { width: 100%; border-collapse: collapse; }
        .summary th, .summary td, .table th, .table td { border: 1px solid #d1d5db; padding: 6px 8px; }
        .summary th, .table th { background: #f5f3ff; text-align: left; }
        .text-right { text-align: right; }
        .muted { color: #6b7280; }
        .mb-8 { margin-bottom: 8px; }
    </style>
</head>
<body>
    <h1>Dashboard Summary Report ({{ $periodLabel }})</h1>
    <div class="meta">
        <div><strong>Coverage:</strong> {{ $start->format('M d, Y h:i A') }} to {{ $end->format('M d, Y h:i A') }}</div>
        <div><strong>Generated:</strong> {{ $generatedAt->format('M d, Y h:i A') }}</div>
    </div>

    <table class="summary mb-8">
        <tr><th>Total Medicines</th><td class="text-right">{{ number_format($stats['total_medicines']) }}</td></tr>
        <tr><th>Total Inventory Items</th><td class="text-right">{{ number_format($stats['total_inventory']) }}</td></tr>
        <tr><th>Total Sales ({{ $periodLabel }})</th><td class="text-right">{{ number_format($stats['total_sales']) }}</td></tr>
        <tr><th>Total Units Sold ({{ $periodLabel }})</th><td class="text-right">{{ number_format($stats['total_units_sold']) }}</td></tr>
        <tr><th>Total Suppliers</th><td class="text-right">{{ number_format($stats['total_suppliers']) }}</td></tr>
        <tr><th>Low Stock Items</th><td class="text-right">{{ number_format($stats['low_stock']) }}</td></tr>
        <tr><th>Total Revenue ({{ $periodLabel }})</th><td class="text-right">₱{{ number_format($stats['total_revenue'], 2) }}</td></tr>
        <tr><th>Average Sale Value ({{ $periodLabel }})</th><td class="text-right">₱{{ number_format($averageSale, 2) }}</td></tr>
    </table>

    <div class="section">
        <h2>Top Medicines by Quantity ({{ $periodLabel }})</h2>
        @if($topMedicines->isEmpty())
            <p class="muted">No sales data available for this period.</p>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>Medicine</th>
                        <th class="text-right">Units Sold</th>
                        <th class="text-right">Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topMedicines as $row)
                        <tr>
                            <td>{{ $row->medicine_name }}</td>
                            <td class="text-right">{{ number_format((float) $row->total_quantity) }}</td>
                            <td class="text-right">₱{{ number_format((float) $row->total_revenue, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div class="section">
        <h2>Detailed Sales Transactions ({{ $periodLabel }})</h2>
        @if($detailedSales->isEmpty())
            <p class="muted">No detailed transactions found for this period.</p>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Medicine</th>
                        <th>Customer</th>
                        <th class="text-right">Qty</th>
                        <th class="text-right">Unit Price</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($detailedSales as $sale)
                        @php
                            $saleDate = $sale->sale_date ?: $sale->created_at;
                        @endphp
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($saleDate)->format('M d, Y') }}</td>
                            <td>{{ $sale->medicine_name }}</td>
                            <td>{{ $sale->customer_name }}</td>
                            <td class="text-right">{{ number_format((float) $sale->quantity) }}</td>
                            <td class="text-right">₱{{ number_format((float) $sale->unit_price, 2) }}</td>
                            <td class="text-right">₱{{ number_format((float) $sale->total_amount, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</body>
</html>
