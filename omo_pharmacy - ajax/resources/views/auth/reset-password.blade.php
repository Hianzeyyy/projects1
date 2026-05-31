<!DOCTYPE html>
<html lang="<?= e(str_replace('_', '-', app()->getLocale())) ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?= e(csrf_token()) ?>">
    <title>Set New Password - PharmaSys</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700,800&display=swap" rel="stylesheet" />
    <?php echo app(\Illuminate\Foundation\Vite::class)(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="auth-page">
    <?php echo '<div class="register-container">'; ?>
        <?php echo '<div class="register-header">'; ?>
            <h1>Create a new password</h1>
            <p>Use a strong password to secure your account.</p>
        <?php echo '</div>'; ?>

        <div data-auth-success class="error" style="background:#dcfce7; border-color:#86efac; color:#166534; display:none;"></div>
        <div data-auth-error class="error" style="display:none;"></div>

        <form method="POST" action="<?= e(route('password.store', [], false)) ?>" data-auth-ajax="reset-password" data-auth-loading-text="Resetting password...">
            <?= csrf_field() ?>
            <input type="hidden" name="token" value="<?= e($request->route('token')) ?>">

            <?php echo '<div class="form-group">'; ?>
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= e(old('email', $request->email)) ?>" required autofocus autocomplete="username">
                <?php $__key = 'email'; if ($errors->has($__key)): foreach ($errors->get($__key) as $message): ?>
                    <?php echo '<div class="error-text">'; ?><?= e($message) ?><?php echo '</div>'; ?>
                <?php endforeach; endif; ?>
            <?php echo '</div>'; ?>

            <?php echo '<div class="form-group">'; ?>
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required autocomplete="new-password">
                <?php $__key = 'password'; if ($errors->has($__key)): foreach ($errors->get($__key) as $message): ?>
                    <?php echo '<div class="error-text">'; ?><?= e($message) ?><?php echo '</div>'; ?>
                <?php endforeach; endif; ?>
            <?php echo '</div>'; ?>

            <?php echo '<div class="form-group">'; ?>
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password">
                <?php $__key = 'password_confirmation'; if ($errors->has($__key)): foreach ($errors->get($__key) as $message): ?>
                    <?php echo '<div class="error-text">'; ?><?= e($message) ?><?php echo '</div>'; ?>
                <?php endforeach; endif; ?>
            <?php echo '</div>'; ?>

            <button type="submit" class="btn-register">Reset Password</button>
        </form>
    <?php echo '</div>'; ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.pharmaAjax) {
                window.pharmaAjax.initAuthAjax();
            }
        });
    </script>
</body>
</html>
