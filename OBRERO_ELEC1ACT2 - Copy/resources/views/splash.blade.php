<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PharmaSys - Loading</title>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet">
    <link rel="stylesheet" href="/css/app.css">

    <!-- All splash logic moved to app.js -->
</head>
<body class="splash-page">
    <div class="splash-card" id="splash-card" data-auth="{{ auth()->check() ? '1' : '0' }}">
        <div class="progress-container">
            <div class="progress-fill"></div>
        </div>
        <div class="loading-status">INITIALIZING SYSTEM...</div>
    </div>
<script src="/js/app.js"></script>
</body>
</html>