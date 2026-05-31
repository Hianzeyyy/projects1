<!DOCTYPE html>
<html lang="<?= e(str_replace('_', '-', app()->getLocale())) ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?= e(csrf_token()) ?>">
    <title><?= e(config('app.name', 'Pharmacy')) ?></title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=lexend:400,500,600,700,800&display=swap" rel="stylesheet" />
    <?php echo app(\Illuminate\Foundation\Vite::class)(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>

<body class="app-shell <?= e(trim($bodyClass ?? '')) ?>">
    <nav class="portal-navbar">
        <?php echo '<div class="portal-brand">'; ?>
            <?php echo '<div class="portal-brand-mark">'; ?>💊<?php echo '</div>'; ?>
            <?php echo '<div class="portal-brand-wordmark">'; ?>
                <?php echo '<div class="portal-brand-main">'; ?>PHARMACY<?php echo '</div>'; ?>
                <?php echo '<div class="portal-brand-sub">'; ?>Pharmacy Management Portal<?php echo '</div>'; ?>
            <?php echo '</div>'; ?>
        <?php echo '</div>'; ?>

        <?php echo '<div class="portal-actions">'; ?>
            <?php echo '<div class="portal-dropdown-group" data-portal-dropdown-group>'; ?>
                <button type="button" class="portal-dropdown-button" data-portal-dropdown-toggle aria-expanded="false">MENU</button>
                <?php echo '<div class="portal-dropdown-panel" data-portal-dropdown-panel hidden>'; ?>
                    <a href="<?= e(route('dashboard')) ?>" class="<?= e(request()->routeIs('dashboard') ? 'active' : '') ?>">Home</a>
                    <a href="<?= e(route('records.medicines')) ?>" class="<?= e(request()->routeIs('medicines.*') ? 'active' : '') ?>">Medicines</a>
                    <a href="<?= e(route('records.inventory')) ?>" class="<?= e(request()->routeIs('records.inventory') ? 'active' : '') ?>">Inventory</a>
                    <a href="<?= e(route('records.sales')) ?>" class="<?= e(request()->routeIs('sales.*') || request()->routeIs('records.sales') ? 'active' : '') ?>">Sales</a>
                    <a href="<?= e(route('records.suppliers')) ?>" class="<?= e(request()->routeIs('suppliers.*') || request()->routeIs('records.suppliers') ? 'active' : '') ?>">Suppliers</a>
                    <a href="<?= e(route('reports.index')) ?>" class="<?= e(request()->routeIs('reports.*') ? 'active' : '') ?>">Reports</a>
                    <a href="<?= e(route('ai.index')) ?>" class="<?= e(request()->routeIs('ai.*') ? 'active' : '') ?>">AI Process</a>
                <?php echo '</div>'; ?>
            <?php echo '</div>'; ?>

            <form method="POST" action="<?= e(route('logout')) ?>" class="portal-navbar-logout-form">
                <?= csrf_field() ?>
                <button type="submit" class="portal-dropdown-button portal-navbar-logout-btn"><span class="portal-logout-icon" aria-hidden="true">&#x21AA;</span> LOGOUT</button>
            </form>
        <?php echo '</div>'; ?>
    </nav>

    <?php echo '<div class="container">'; ?>
        <?php echo '<div class="main-wrapper">'; ?>
            <?php if (isset($header)): ?>
                <header class="topbar">
                    <?php echo '<div class="topbar-left">'; ?>
                        <button class="menu-toggle" type="button" aria-label="Toggle navigation" data-sidebar-toggle>☰</button>
                        <?php echo '<div class="topbar-badge">'; ?>Pharma Panel<?php echo '</div>'; ?>
                    <?php echo '</div>'; ?>
                    <?php echo '<div class="header-content">'; ?><?= $header ?? '' ?><?php echo '</div>'; ?>
                    <?php echo '<div class="topbar-right">'; ?><?php echo '</div>'; ?>
                </header>
            <?php endif; ?>

            <main class="content">
                <?= $slot ?? '' ?>
            </main>

            <footer class="app-footer">
                <p>&copy; 2026 Pharmacy</p>
            </footer>
        <?php echo '</div>'; ?>
    <?php echo '</div>'; ?>
</body>

</html>
