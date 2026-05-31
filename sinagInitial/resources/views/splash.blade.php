<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Splash Screen</title>
    <style>
        body {
            margin: 0;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #6a5af9 0%, #c43aff 100%);
            font-family: 'Montserrat', Arial, sans-serif;
        }
        .splash-container {
            text-align: center;
            color: #fff;
        }
        .splash-icon {
            background: #fff;
            border-radius: 24px;
            width: 120px;
            height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 32px auto;
            box-shadow: 0 8px 32px rgba(0,0,0,0.12);
        }
        .splash-icon svg {
            width: 56px;
            height: 56px;
            stroke: #6a5af9;
        }
        .splash-title {
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 12px;
        }
        .splash-desc {
            font-size: 1.25rem;
            margin-bottom: 8px;
        }
        .splash-campus {
            font-size: 1rem;
            margin-bottom: 32px;
        }
        .splash-loading {
            font-size: 1.1rem;
            margin-bottom: 12px;
        }
        .splash-dots {
            display: flex;
            justify-content: center;
            gap: 8px;
        }
        .splash-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #fff;
            opacity: 0.4;
            transition: opacity 0.3s;
        }
        .splash-dot.active {
            opacity: 1;
            background: #e05aff;
        }
        @keyframes splashDot {
            0%, 80%, 100% { opacity: 0.4; }
            40% { opacity: 1; }
        }
        .splash-dot {
            animation: splashDot 1.4s infinite both;
        }
        .splash-dot:nth-child(1) { animation-delay: 0s; }
        .splash-dot:nth-child(2) { animation-delay: 0.2s; }
        .splash-dot:nth-child(3) { animation-delay: 0.4s; }
            background: #c43aff;
        }
    </style>
</head>
<body>
    <div class="splash-container">
        <div class="splash-icon" style="background: transparent; box-shadow: none;">
            <img src="/images/logo.png" alt="SINAG Logo" style="width: 120px; height: 60px; object-fit: contain;" />
        </div>
        <div class="splash-title">SINAG</div>
        <div class="splash-desc">Safe Incident Notification & Awareness Gateway</div>
        <div class="splash-campus">PSU Asingan Campus</div>
        <div class="splash-dots">
            <div class="splash-dot"></div>
            <div class="splash-dot"></div>
            <div class="splash-dot"></div>
        </div>
        <noscript>
            <div style="margin-top: 24px;">
                <a href="/login" style="color: #fff; text-decoration: underline; font-size: 1.1rem;">Go to Login (JavaScript required for auto-redirect)</a>
            </div>
        </noscript>
    </div>
    <script>
        // Force logout if user is authenticated, then redirect to login
        fetch('/logout', {method: 'POST', headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}})
            .finally(function() {
                setTimeout(function() {
                    window.location.href = "/login";
                }, 3000);
            });
</script>
   
</body>
</html>
