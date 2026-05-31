<!DOCTYPE html>
<html lang="<?= e(str_replace('_', '-', app()->getLocale())) ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign Up - PharmaSys</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=lexend:400,500,600,700,800&display=swap" rel="stylesheet" />
    <?php echo app(\Illuminate\Foundation\Vite::class)(['resources/css/app.css']); ?>
</head>
<body class="auth-page" style="min-height:100vh;display:flex;align-items:center;justify-content:center;background:linear-gradient(140deg,#ede9fe 0%,#a78bfa 100%)">
    <div style="background:#fff;border-radius:24px;box-shadow:0 8px 32px #a78bfa33;padding:2.5rem 2.5rem 2rem 2.5rem;max-width:400px;width:100%;display:flex;flex-direction:column;align-items:center;">
        <div style="background:linear-gradient(135deg,#a78bfa,#7c3aed);box-shadow:0 4px 24px #a78bfa44;padding:18px;border-radius:16px;display:inline-block;margin-bottom:1.5rem;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#7c3aed" width="48" height="48">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
        </div>
        <h1 style="font-size:2rem;font-weight:600;color:#7c3aed;margin-bottom:0.25rem;">Create account</h1>
        <p style="color:#7c3aed;font-size:1.08rem;margin-bottom:1.5rem;">Join PharmaSys and start managing your PharmaSys</p>
        <?php if ($errors->any()): ?>
            <div class="error" style="width:100%;margin-bottom:1rem;">
                Please fix the errors below
            </div>
        <?php endif; ?>
        <form method="POST" action="<?= e(route('register')) ?>" style="width:100%;display:flex;flex-direction:column;gap:1.1rem;">
            <?= csrf_field() ?>
            <div class="form-group" style="width:100%;">
                <label for="name" style="font-weight:500;color:#7c3aed;">Name</label>
                <input type="text" id="name" name="name" value="<?= e(old('name')) ?>" required placeholder="Enter your name" autofocus style="width:100%;padding:0.7rem 1rem;border-radius:10px;border:1.5px solid #a78bfa;font-size:1rem;margin-top:0.2rem;">
                <?php $__key = 'name'; if ($errors->has($__key)): foreach ($errors->get($__key) as $message): ?>
                    <div class="error-text" style="color:#b91c1c;font-size:0.9rem;"><?= e($message) ?></div>
                <?php endforeach; endif; ?>
            </div>
            <div class="form-group" style="width:100%;">
                <label for="email" style="font-weight:500;color:#7c3aed;">Email</label>
                <input type="email" id="email" name="email" value="<?= e(old('email')) ?>" required placeholder="your.email@example.com" style="width:100%;padding:0.7rem 1rem;border-radius:10px;border:1.5px solid #a78bfa;font-size:1rem;margin-top:0.2rem;">
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
            </div>
            <div class="form-group" style="width:100%;">
                <label for="password_confirmation" style="font-weight:500;color:#7c3aed;">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Confirm your password" style="width:100%;padding:0.7rem 1rem;border-radius:10px;border:1.5px solid #a78bfa;font-size:1rem;margin-top:0.2rem;">
                <?php $__key = 'password_confirmation'; if ($errors->has($__key)): foreach ($errors->get($__key) as $message): ?>
                    <div class="error-text" style="color:#b91c1c;font-size:0.9rem;"><?= e($message) ?></div>
                <?php endforeach; endif; ?>
            </div>
            <button type="submit" class="btn-register" style="margin-top:0.5rem;width:100%;background:linear-gradient(135deg,#a21caf,#7c3aed);border:4px solid #fff;color:#fff;font-size:1.15rem;font-weight:600;padding:0.8rem 0;border-radius:16px;box-shadow:0 2px 8px #a78bfa22;transition:background 0.2s;">Sign up</button>
        </form>
        <div class="login-link" style="margin-top:1.5rem;color:#7c3aed;font-size:1.01rem;">
            Already have an account? <a href="<?= e(route('login')) ?>" style="color:#7c3aed;font-weight:600;">Sign in</a>
        </div>
    </div>
</body>
</html>
