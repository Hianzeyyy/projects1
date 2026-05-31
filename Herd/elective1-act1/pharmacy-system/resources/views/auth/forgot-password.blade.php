<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forgot Password - PharmaSys</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css'])
</head>
<body class="auth-page">
    <div class="login-container">
        <div class="login-header">
            <h1>Reset your password</h1>
            <p>Enter your email and we will send a password reset link.</p>
        </div>

        @if (session('status'))
            <div class="error" style="background:#dcfce7; border-color:#86efac; color:#166534;">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="your.email@example.com">
                @error('email')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn-login">Email Password Reset Link</button>
        </form>

        <div class="signup-link">
            Back to <a href="{{ route('login') }}">Sign in</a>
        </div>
    </div>
</body>
</html>
