<!DOCTYPE html>
<html lang="<?= e(str_replace('_', '-', app()->getLocale())) ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?= e(csrf_token()) ?>">
    <title>Sign Up - PharmaSys</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=lexend:400,500,600,700,800&display=swap" rel="stylesheet" />
    <?php echo app(\Illuminate\Foundation\Vite::class)(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="auth-page">
    <?php echo '<div class="register-container">'; ?>
        <?php echo '<div class="icon">'; ?>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
        <?php echo '</div>'; ?>

        <?php echo '<div class="register-header">'; ?>
            <h1>Create account</h1>
            <p>Join Pharmacy and start managing your pharmacy</p>
        <?php echo '</div>'; ?>

        <?php if ($errors->any()): ?>
            <?php echo '<div class="error">'; ?>
                Please fix the errors below
            <?php echo '</div>'; ?>
        <?php endif; ?>

        <div data-auth-success class="error" style="background:#dcfce7; border-color:#86efac; color:#166534; display:none;"></div>
        <div data-auth-error class="error" style="display:none;"></div>

        <form method="POST" action="<?= e(route('register', [], false)) ?>" data-auth-ajax="register" data-auth-loading-text="Creating account...">
            <?= csrf_field() ?>

            <?php echo '<div class="form-group">'; ?>
                <label for="name">Name</label>
                <input type="text" id="name" name="name" value="<?= e(old('name')) ?>" required placeholder="Enter your name" autofocus>
                <?php $__key = 'name'; if ($errors->has($__key)): foreach ($errors->get($__key) as $message): ?>
                    <?php echo '<div class="error-text">'; ?><?= e($message) ?><?php echo '</div>'; ?>
                <?php endforeach; endif; ?>
            <?php echo '</div>'; ?>

            <?php echo '<div class="form-group">'; ?>
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= e(old('email')) ?>" required placeholder="your.email@example.com">
                <?php $__key = 'email'; if ($errors->has($__key)): foreach ($errors->get($__key) as $message): ?>
                    <?php echo '<div class="error-text">'; ?><?= e($message) ?><?php echo '</div>'; ?>
                <?php endforeach; endif; ?>
            <?php echo '</div>'; ?>

            <?php echo '<div class="form-group">'; ?>
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required placeholder="Enter your password">
                <?php $__key = 'password'; if ($errors->has($__key)): foreach ($errors->get($__key) as $message): ?>
                    <?php echo '<div class="error-text">'; ?><?= e($message) ?><?php echo '</div>'; ?>
                <?php endforeach; endif; ?>
            <?php echo '</div>'; ?>

            <?php echo '<div class="form-group">'; ?>
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Confirm your password">
                <?php $__key = 'password_confirmation'; if ($errors->has($__key)): foreach ($errors->get($__key) as $message): ?>
                    <?php echo '<div class="error-text">'; ?><?= e($message) ?><?php echo '</div>'; ?>
                <?php endforeach; endif; ?>
            <?php echo '</div>'; ?>

            <button type="submit" class="btn-register">Sign up</button>
        </form>

        <?php echo '<div class="login-link">'; ?>
            Already have an account? <a href="<?= e(route('login')) ?>">Sign in</a>
        <?php echo '</div>'; ?>
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
