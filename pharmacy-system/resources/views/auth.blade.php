@extends('layouts.auth')

@section('auth_title')
    @if ($type === 'register')
        Create account
    @elseif ($type === 'forgot-password')
        Forgot your password?
    @elseif ($type === 'verify-email')
        Verify your email
    @else
        Welcome back
    @endif
@endsection

@section('auth_subtitle')
    @if ($type === 'register')
        Sign up for PharmaSys
    @elseif ($type === 'forgot-password')
        Enter your email for a reset link.
    @elseif ($type === 'verify-email')
        Check your inbox for a link.
    @else
        Sign in to your PharmaSys account
    @endif
@endsection

@section('auth_content')
    @if ($type === 'register')
        @if ($errors->any())
            <div class="error">Please fix the errors below</div>
        @endif
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="Enter your name">
                @if ($errors->has('name'))
                    <div class="error-text">{{ $errors->first('name') }}</div>
                @endif
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="your.email@example.com">
                @if ($errors->has('email'))
                    <div class="error-text">{{ $errors->first('email') }}</div>
                @endif
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required placeholder="Enter your password">
            </div>
            <button class="btn-main" type="submit">Sign up</button>
        </form>
    @elseif ($type === 'forgot-password')
        @if (session('status'))
            <div class="error" style="background:#ecfdf3; color:#166534;">{{ session('status') }}</div>
        @endif
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="your.email@example.com">
            </div>
            <button class="btn-main" type="submit">Send Reset Link</button>
        </form>
    @elseif ($type === 'verify-email')
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button class="btn-main" type="submit">Resend Verification Email</button>
        </form>
    @else
        @if ($errors->any())
            <div class="error">{{ $errors->first() }}</div>
        @endif
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="your.email@example.com">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required placeholder="Enter your password">
            </div>
            <button class="btn-main" type="submit">Sign in</button>
        </form>
    @endif
@endsection

@section('auth_links')
    @if ($type === 'register')
        Already have an account? <a href="{{ route('login') }}">Sign in</a>
    @elseif ($type === 'forgot-password')
        <a href="{{ route('login') }}">Back to sign in</a>
    @elseif ($type === 'verify-email')
        <a href="{{ route('login') }}">Back to sign in</a>
    @else
        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}">Forgot password?</a><br>
        @endif
        Don't have an account? <a href="{{ route('register') }}">Sign up</a>
    @endif
@endsection
