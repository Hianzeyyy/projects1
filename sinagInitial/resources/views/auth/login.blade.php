<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SINAG - Login</title>
    <style>
        html, body {
            height: 100%;
        }
        body {
            min-height: 100vh;
            margin: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: linear-gradient(120deg, #7b5fff 0%, #a685ff 50%, #e14fff 100%);
            font-family: 'Montserrat', Arial, sans-serif;
        }
        .login-container {
            width: 100vw;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #fff;
        }
        .login-logo-box {
            background: #fff;
            border-radius: 32px;
            width: 120px;
            height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 32px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.10);
        }
        .login-logo-box img {
            width: 70px;
            height: 70px;
            object-fit: contain;
        }
        .login-title {
            font-size: 3.2rem;
            font-weight: 900;
            letter-spacing: 0.04em;
            margin-bottom: 0.2em;
        }
        .login-desc {
            font-size: 1.25rem;
            margin-bottom: 32px;
            font-weight: 500;
        }
        .login-form {
            background: #fff;
            color: #222;
            border-radius: 28px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.13);
            padding: 36px 32px 28px 32px;
            max-width: 520px;
            width: 50%;
            margin: 0 auto;
        }
        .login-form label {
            font-weight: 700;
            font-size: 1.1rem;
            display: block;
            text-align: left;
            margin-bottom: 4px;
        }
        .login-form input {
            width: 100%;
            padding: 12px;
            margin-bottom: 18px;
            border-radius: 10px;
            border: 2px solid #bbb;
            font-size: 1.08rem;
        }
        .login-form button {
            width: 100%;
            padding: 14px;
            background: #5f3fff;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 1.25rem;
            font-weight: bold;
            cursor: pointer;
            margin-top: 10px;
            box-shadow: 0 2px 8px rgba(95,63,255,0.10);
            transition: background 0.2s;
        }
        .login-form button:hover {
            background: #6a5af9;
        }
        .login-form .form-footer {
            margin-top: 18px;
            font-size: 1.05rem;
        }
        .login-form .form-footer a {
            color: #fff;
            background: #5f3fff;
            padding: 6px 18px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            margin-left: 8px;
            transition: background 0.2s;
        }
        .login-form .form-footer a:hover {
            background: #6a5af9;
        }
        .login-legal {
            margin-top: 32px;
            font-size: 0.95rem;
            color: #e0e0e0;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-logo-box">
            <img src="/images/logo.png" alt="SINAG Logo" />
        </div>
        <div class="login-title">SINAG</div>
        <div class="login-desc">Sign in to continue</div>
        <form class="login-form" id="loginForm" method="POST" action="{{ route('login') }}">
            @csrf
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" placeholder="student@psu.edu.ph" required autofocus>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="••••••••" required>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                <div style="display: flex; align-items: center; gap: 8px; line-height: 1;">
                    <input type="checkbox" id="remember" name="remember" style="width: 20px; height: 20px; margin: 0; vertical-align: middle;">
                    <label for="remember" style="font-size: 1.1rem; font-weight: bold; margin: 0; display: flex; align-items: center; height: 20px;">Remember me</label>
                </div>
                <a href="{{ route('password.request') }}" style="font-size: 1rem; color: #5f3fff; text-decoration: underline;">Forgot password?</a>
            </div>
            <button type="submit">Sign In</button>
            <div class="form-footer" style="text-align:left; margin-top:24px;">
                <span style="color:#555; font-size:1.15rem;">New to SINAG?</span>
                <a href="{{ route('register') }}" style="color:#6a35f9; font-weight:700; font-size:1.15rem; text-decoration:none; margin-left:8px; cursor:pointer; background:none; border:none; box-shadow:none; padding:0;">Create an Account</a>
            </div>
        </form>
        <div class="login-legal">
            By signing in, you agree to the Safe Spaces Act (RA 11313) data privacy protocols and PSU's Code of Conduct.
        </div>
    </div>
</body>
</html>
