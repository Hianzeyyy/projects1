<!DOCTYPE html>
<html lang="<?= e(str_replace('_', '-', app()->getLocale())) ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - PharmaSys</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=lexend:400,500,600,700,800&display=swap" rel="stylesheet" />
    <?php echo app(\Illuminate\Foundation\Vite::class)(['resources/css/app.css']); ?>
</head>
<body class="auth-page" style="min-height:100vh;display:flex;align-items:center;justify-content:center;background:linear-gradient(140deg,#f5f3ff 0%,#a21caf 100%)">
    <div style="background:#fff;border-radius:24px;box-shadow:0 8px 32px #a21caf33;padding:2.5rem 2.5rem 2rem 2.5rem;max-width:400px;width:100%;display:flex;flex-direction:column;align-items:center;">
        <div style="background:linear-gradient(135deg,#a21caf,#7c3aed);box-shadow:0 4px 24px #a21caf44;padding:18px;border-radius:16px;display:inline-block;margin-bottom:1.5rem;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#7c3aed" width="48" height="48">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
        </div>
        <h1 style="font-size:2rem;font-weight:600;color:#7c3aed;margin-bottom:0.25rem;">Welcome back</h1>
        <p style="color:#7c3aed;font-size:1.08rem;margin-bottom:1.5rem;">Sign in to your PharmaSYS account</p>
        <?php if ($errors->any()): ?>
            <div class="error" style="width:100%;margin-bottom:1rem;">
                <?= e($errors->first()) ?>
            </div>
        <?php endif; ?>
        <form method="POST" action="<?= e(route('login')) ?>" style="width:100%;display:flex;flex-direction:column;gap:1.1rem;">
            <?= csrf_field() ?>
            <div class="form-group" style="width:100%;">
                <label for="email" style="font-weight:500;color:#7c3aed;">Email</label>
                <input type="email" id="email" name="email" value="<?= e(old('email')) ?>" required placeholder="your.email@example.com" autofocus style="width:100%;padding:0.7rem 1rem;border-radius:10px;border:1.5px solid #a78bfa;font-size:1rem;margin-top:0.2rem;">
                <?php $__key = 'email'; if ($errors->has($__key)): foreach ($errors->get($__key) as $message): ?>
                    <div class="error-text" style="color:#b91c1c;font-size:0.9rem;"><?= e($message) ?></div>
                <?php endforeach; endif; ?>
            </div>
            <div class="form-group" style="width:100%;">
                <label for="password" style="font-weight:500;color:#7c3aed;">Password</label>
                <input type="password" id="password" name="password" required placeholder="Enter your password" style="width:100%;padding:0.7rem 1rem;border-radius:10px;border:1.5px solid #a78bfa;font-size:1rem;margin-top:0.2rem;">
                <?php $__key = 'password'; if ($errors->has($__key)): foreach ($errors->get($__key) as $message): ?>
                    <div class="error-text" style="color:#b91c1c;font-size:0.9rem;"><?= e($message) ?></div>
                <?php endforeach; endif; ?>
                <?php if (Route::has('password.request')): ?>
                    <a href="<?= e(route('password.request')) ?>" class="forgot-password" style="display:block;margin-top:0.5rem;font-size:0.98rem;color:#7c3aed;font-weight:500;">Forgot password?</a>
                <?php endif; ?>
            </div>
            <button type="submit" class="btn-login" style="margin-top:0.5rem;width:100%;background:linear-gradient(135deg,#a21caf,#7c3aed);border:4px solid #fff;color:#fff;font-size:1.15rem;font-weight:600;padding:0.8rem 0;border-radius:16px;box-shadow:0 2px 8px #a78bfa22;transition:background 0.2s;">Sign in</button>
        </form>
        <div class="signup-link" style="margin-top:1.5rem;color:#7c3aed;font-size:1.01rem;">
            Don't have an account? <a href="<?= e(route('register')) ?>" style="color:#7c3aed;font-weight:600;">Sign up</a>
        </div>
    </div>
</body>
</html>
