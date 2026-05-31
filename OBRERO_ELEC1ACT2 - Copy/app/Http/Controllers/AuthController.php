<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // ── Show pages ─────────────────────────────────────────────────────

    public function showLogin()
    {
        return response(require resource_path('views/auth/login.php'))
            ->header('Content-Type', 'text/html; charset=UTF-8');
    }

    public function showSignup()
    {
        return response(require resource_path('views/auth/signup.php'))
            ->header('Content-Type', 'text/html; charset=UTF-8');
    }

    public function showForgotPassword()
    {
        return response(require resource_path('views/auth/forgot-password.php'))
            ->header('Content-Type', 'text/html; charset=UTF-8');
    }

    // ── Process forms ──────────────────────────────────────────────────

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()
            ->withErrors(['email' => 'Invalid email or password.'])
            ->withInput($request->only('email'));
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create($data);
        Auth::login($user);
        $request->session()->regenerate();

        return redirect('/');
    }

    public function forgotPassword(Request $request)
    {
        $data = $request->validate([
            'email'    => ['required', 'email', 'exists:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::where('email', $data['email'])->firstOrFail();
        $user->password = $data['password'];   // model casts to hashed automatically
        $user->save();

        return redirect('/login')
            ->with('status', 'Password reset successfully. Please sign in with your new password.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
