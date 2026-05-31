<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PharmaSys Auth</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body { background: #f0fdf4; min-height: 100vh; }
        .auth-center {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .auth-card {
            background: #fff;
            border-radius: 2rem;
            box-shadow: 0 8px 32px 0 rgba(16, 185, 129, 0.12);
            padding: 2.5rem 2rem 2rem 2rem;
            max-width: 400px;
            width: 100%;
            margin: 2rem auto;
        }
        .auth-card .icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #16a34a 0%, #0e7490 100%);
            border-radius: 50%;
            margin: 0 auto 1.5rem auto;
        }
        .auth-card .icon img {
            width: 40px;
            height: 40px;
        }
        .auth-card h1 {
            font-size: 2rem;
            font-weight: 800;
            text-align: center;
            margin-bottom: 0.5rem;
        }
        .auth-card p {
            text-align: center;
            color: #64748b;
            margin-bottom: 2rem;
        }
        .auth-card .form-group {
            margin-bottom: 1.2rem;
        }
        .auth-card label {
            font-weight: 600;
            margin-bottom: 0.3rem;
            display: block;
        }
        .auth-card input {
            width: 100%;
            padding: 0.7rem 1rem;
            border-radius: 0.7rem;
            border: 1px solid #e5e7eb;
            background: #f1f5f9;
            font-size: 1rem;
        }
        .auth-card .btn-main {
            width: 100%;
            background: linear-gradient(90deg, #16a34a 0%, #0e7490 100%);
            color: #fff;
            font-weight: 700;
            border: none;
            border-radius: 0.7rem;
            padding: 0.9rem 0;
            font-size: 1.1rem;
            margin-top: 1.2rem;
            cursor: pointer;
            transition: background 0.2s;
        }
        .auth-card .btn-main:hover {
            background: linear-gradient(90deg, #0e7490 0%, #16a34a 100%);
        }
        .auth-card .auth-links {
            text-align: center;
            margin-top: 1.5rem;
        }
        .auth-card .auth-links a {
            color: #0e7490;
            font-weight: 600;
            text-decoration: none;
        }
        .auth-card .auth-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="auth-center">
    <div class="auth-card">
        <div class="icon">
            <img src="{{ asset('images/pharmasys-icon.png') }}" alt="PharmaSys">
        </div>
        <h1>@yield('auth_title', 'Welcome back')</h1>
        <p>@yield('auth_subtitle', 'Sign in to your PharmaSys account')</p>
        @yield('auth_content')
        <div class="auth-links">
            @yield('auth_links')
        </div>
    </div>
</div>
</body>
</html>
