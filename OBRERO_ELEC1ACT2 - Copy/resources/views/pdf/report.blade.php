<html>
<head>
    <title>Report PDF</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        h1 { color: #7c3aed; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; }
        th { background: #f5f3ff; }
    </style>
</head>
<body>
    <h1>Report #{{ $report->id }}</h1>
    <p><strong>User ID:</strong> {{ $report->user_id }}</p>
    <p><strong>Type:</strong> {{ $report->type }}</p>
    <p><strong>Generated At:</strong> {{ $report->generated_at }}</p>
    <h2>Data</h2>
    <pre>{{ $report->data }}</pre>
</body>
</html>
