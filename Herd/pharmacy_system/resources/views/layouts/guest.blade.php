<!DOCTYPE html>
<html lang="<?= str_replace('_', '-', app()->getLocale()) ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?= csrf_token() ?>">

        <title><?= config('app.name', 'Laravel') ?></title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=lexend:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        <?php echo app(\Illuminate\Foundation\Vite::class)(['resources/css/app.css', 'resources/js/app.js']); ?>
    </head>
    <body class="auth-page guest-page">
        <?php echo '<div class="guest-shell">'; ?>
            <?php echo '<div>'; ?>
                <a href="/">
                    <span class="app-logo-text">PharmaSys</span>
                </a>
            <?php echo '</div>'; ?>

            <?php echo '<div class="guest-card w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">'; ?>
                <?= $slot ?? "" ?>
            <?php echo '</div>'; ?>
        <?php echo '</div>'; ?>
    </body>
</html>
