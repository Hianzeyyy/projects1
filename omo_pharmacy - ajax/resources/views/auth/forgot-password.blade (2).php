<!DOCTYPE html>
<html lang="<?= e(str_replace('_', '-', app()->getLocale())) ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forgot Password - PharmaSys</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700,800&display=swap" rel="stylesheet" />
    <?php echo app(\Illuminate\Foundation\Vite::class)(['resources/css/app.css']); ?>
</head>
<body class="auth-page">
    <?php echo '<div class="login-container">'; ?>
        <?php echo '<div class="login-header">'; ?>
            <h1>Reset your password</h1>
            <p>Enter your email and we will send a password reset link.</p>
        <?php echo '</div>'; ?>

        <?php if (session('status')): ?>
            <?php echo '<div class="error" style="background:#dbeafe; border-color:#93c5fd; color:#1d4ed8;">'; ?>
                <?= e(session('status')) ?>
            <?php echo '</div>'; ?>
        <?php endif; ?>

        <form method="POST" action="<?= e(route('password.email')) ?>">
            <?= csrf_field() ?>

            <?php echo '<div class="form-group">'; ?>
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= e(old('email')) ?>" required autofocus placeholder="your.email@example.com">
                <?php $__key = 'email'; if ($errors->has($__key)): foreach ($errors->get($__key) as $message): ?>
                    <?php echo '<div class="error-text">'; ?><?= e($message) ?><?php echo '</div>'; ?>
                <?php endforeach; endif; ?>
            <?php echo '</div>'; ?>

            <button type="submit" class="btn-login">Email Password Reset Link</button>
        </form>

        <?php echo '<div class="signup-link">'; ?>
            Back to <a href="<?= e(route('login')) ?>">Sign in</a>
        <?php echo '</div>'; ?>
    <?php echo '</div>'; ?>
</body>
</html>
