<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pharmacy Dashboard</title>
    <style>
        :root {
            --bg: #f4f7fb;
            --panel: #ffffff;
            --ink: #0f172a;
            --muted: #64748b;
            --line: #e2e8f0;
            --accent: #10b981;
            --accent-dark: #059669;
            --danger: #ef4444;
            --shadow: 0 20px 45px rgba(15, 23, 42, 0.08);
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background:
                radial-gradient(circle at top left, rgba(16, 185, 129, 0.14), transparent 30%),
                radial-gradient(circle at top right, rgba(59, 130, 246, 0.10), transparent 28%),
                var(--bg);
            color: var(--ink);
        }

        .wrap {
            max-width: 1180px;
            margin: 0 auto;
            padding: 40px 20px 56px;
        }

        .hero {
            background: linear-gradient(135deg, #0f172a, #1f2937 70%, #111827);
            color: #fff;
            border-radius: 28px;
            padding: 32px;
            box-shadow: var(--shadow);
            display: grid;
            gap: 18px;
            margin-bottom: 24px;
        }

        .hero h1 {
            margin: 0;
            font-size: clamp(2rem, 4vw, 3.1rem);
            letter-spacing: -0.04em;
        }

        .hero p {
            margin: 0;
            max-width: 760px;
            color: rgba(255, 255, 255, 0.82);
            line-height: 1.6;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
            margin: 24px 0;
        }

        .stat {
            background: rgba(255, 255, 255, 0.98);
            border: 1px solid var(--line);
            border-radius: 22px;
            padding: 22px;
            box-shadow: var(--shadow);
        }

        .stat label {
            display: block;
            color: var(--muted);
            font-size: 0.88rem;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .stat strong {
            font-size: clamp(2rem, 4vw, 3rem);
            line-height: 1;
        }

        .panel {
            background: var(--panel);
            border: 1px solid var(--line);
            border-radius: 28px;
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .panel-head {
            padding: 24px 26px;
            border-bottom: 1px solid var(--line);
            display: flex;
            justify-content: space-between;
            gap: 16px;
            align-items: center;
            flex-wrap: wrap;
        }

        .panel-head h2 {
            margin: 0;
            font-size: 1.3rem;
        }

        .panel-head span {
            color: var(--muted);
        }

        .table-wrap {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 760px;
        }

        thead th {
            text-align: left;
            padding: 16px 20px;
            font-size: 0.84rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--muted);
            background: #f8fafc;
            border-bottom: 1px solid var(--line);
        }

        tbody td {
            padding: 18px 20px;
            border-bottom: 1px solid var(--line);
            vertical-align: middle;
        }

        tbody tr:hover {
            background: #f9fbfd;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border-radius: 999px;
            padding: 8px 12px;
            font-size: 0.88rem;
            font-weight: 700;
        }

        .badge-ok {
            color: var(--accent-dark);
            background: rgba(16, 185, 129, 0.12);
        }

        .badge-low {
            color: var(--danger);
            background: rgba(239, 68, 68, 0.10);
        }

        .action-link {
            color: var(--danger);
            text-decoration: none;
            font-weight: 700;
        }

        .empty {
            padding: 38px 26px;
            color: var(--muted);
            text-align: center;
        }

        @media (max-width: 720px) {
            .wrap { padding: 18px; }
            .hero, .stat, .panel { border-radius: 20px; }
            .stats { grid-template-columns: 1fr; }
            .panel-head { align-items: flex-start; }
        }
    </style>
</head>
<body>
    <main class="wrap">
        <section class="hero">
            <h1>Pharmacy Inventory Dashboard</h1>
            <p>
                Monitor stock levels, review inventory records, and keep medicine availability under control.
                This page is rendered by the <strong>PharmacyController</strong> using the <strong>pharmacy_main</strong> view.
            </p>
        </section>

        <section class="stats" aria-label="Summary metrics">
            <div class="stat">
                <label>Total medicines</label>
                <strong>{{ $total }}</strong>
            </div>
            <div class="stat">
                <label>Inventory records</label>
                <strong>{{ $records->count() }}</strong>
            </div>
        </section>

        <section class="panel">
            <div class="panel-head">
                <div>
                    <h2>Medicine Records</h2>
                    <span>Current contents of the pharmacy_inventory table</span>
                </div>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Medicine</th>
                            <th>Category</th>
                            <th>Stock</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($records as $record)
                            <tr>
                                <td>{{ $record->id }}</td>
                                <td><strong>{{ $record->med_name }}</strong></td>
                                <td>{{ $record->category }}</td>
                                <td>{{ $record->stock_qty }}</td>
                                <td>₱{{ number_format((float) $record->price, 2) }}</td>
                                <td>
                                    @if ((int) $record->stock_qty < 10)
                                        <span class="badge badge-low">Low stock</span>
                                    @else
                                        <span class="badge badge-ok">In stock</span>
                                    @endif
                                </td>
                                <td>
                                    <a
                                        class="action-link"
                                        href="{{ url('/delete-medicine/' . $record->id) }}"
                                        onclick="return confirm('Delete this record?')"
                                    >
                                        Delete
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty">No inventory records found.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>
</html>
