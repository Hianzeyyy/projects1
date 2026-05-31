<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SINAG - Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/css/theme.css">
</head>
<body class="auth-page">
    <div class="login-bg-gradient min-vh-100 d-flex flex-column justify-content-center align-items-center">
        <div class="text-center mb-4">
            <div class="login-logo-icon mb-3 mx-auto d-flex align-items-center justify-content-center">
                <img src="/images/logo.png" alt="SINAG Logo" style="width: 56px; height: 56px; object-fit: contain; background: #6C63FF; border-radius: 16px;" />
            </div>
            <h1 class="logo-text mb-1" style="font-weight: 800; letter-spacing: 0.04em; font-size:2.2rem;">SINAG</h1>
            <p class="text-muted mb-2" style="font-size: 1.1rem;">Create your account</p>
        </div>
        <div class="auth-card p-4 shadow-sm rounded-4 bg-white mx-auto" style="max-width: 400px; width: 100%;">
            <form id="registerForm" method="POST" action="{{ route('register') }}">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="name">Full Name</label>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0"><i class="fa-regular fa-user"></i></span>
                        <input type="text" class="form-control border-start-0" id="name" name="name" placeholder="Juan Dela Cruz" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="email">PSU Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0"><i class="fa-regular fa-envelope"></i></span>
                        <input type="email" class="form-control border-start-0" id="email" name="email" placeholder="your.email@psu.edu.ph" required>
                    </div>
                    <div class="form-text">Must be a valid @psu.edu.ph email</div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="password">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0"><i class="fa-solid fa-lock"></i></span>
                        <input type="password" class="form-control border-start-0" id="password" name="password" placeholder="Min. 6 characters" required>
                        <span class="input-group-text bg-transparent border-start-0" style="cursor:pointer;" onclick="togglePassword('password', this)"><i class="fa-regular fa-eye"></i></span>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="confirmPassword">Confirm Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0"><i class="fa-solid fa-lock"></i></span>
                        <input type="password" class="form-control border-start-0" id="confirmPassword" name="password_confirmation" placeholder="Re-enter password" required>
                        <span class="input-group-text bg-transparent border-start-0" style="cursor:pointer;" onclick="togglePassword('confirmPassword', this)"><i class="fa-regular fa-eye"></i></span>
                    </div>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="agreeTerms" required>
                    <label class="form-check-label" for="agreeTerms">
                        I agree to the Terms & Privacy Policy
                    </label>
                </div>
                <button type="submit" class="btn w-100 mb-3" style="background:#5f3fff; border-radius: 10px; font-weight:bold; font-size:1.25rem; color:#fff; box-shadow:0 2px 8px rgba(95,63,255,0.10); transition:background 0.2s;">Sign up</button>
                <div class="text-center mb-2" style="margin-top: 24px; text-align: left;">
                    <span style="color:#555; font-size:1.15rem;">Already have an account?</span>
                    <a href="{{ route('login') }}" style="color:#6a35f9; font-weight:700; font-size:1.15rem; text-decoration:none; margin-left:8px; cursor:pointer;">Sign in</a>
                </div>
            </form>
        </div>
    </div>
    <style>
        .login-bg-gradient {
            background: linear-gradient(120deg, #7b5fff 0%, #a685ff 50%, #e14fff 100%);
        }
        .login-logo-icon {
            width: 72px;
            height: 72px;
            background: #f6f4ff;
            border-radius: 24px;
            box-shadow: 0 4px 24px 0 rgba(108,99,255,0.10);
        }
        .auth-card {
            box-shadow: 0 4px 32px 0 rgba(30, 34, 90, 0.10), 0 1.5px 6px 0 rgba(30, 34, 90, 0.04);
        }
    </style>

    <script src="/js/utils.js"></script>
    <script src="/js/auth.js"></script>
</body>
<script>
function togglePassword(fieldId, el) {
    const input = document.getElementById(fieldId);
    const icon = el.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>
</html>
