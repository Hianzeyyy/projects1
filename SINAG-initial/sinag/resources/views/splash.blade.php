<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SINAG Portal - Splash</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8fafc; }
        .splash-logo { max-width: 180px; margin-bottom: 2rem; }
    </style>
    <script>
        setTimeout(function() {
            window.location.href = '/login';
        }, 3000);
    </script>
</head>
<body class="d-flex align-items-center justify-content-center vh-100">
    <div class="text-center">
        <img src="/images/sinag-logo.png" alt="SINAG Logo" class="splash-logo">
        <h1 class="display-5 fw-bold">Welcome to SINAG Portal</h1>
        <p class="lead">PSU Asingan Campus Safety Reporting</p>
        <div class="spinner-border text-primary mt-4" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
</body>
</html>
