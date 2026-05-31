<!DOCTYPE html>
<html lang="<?= e(str_replace('_', '-', app()->getLocale())) ?>" class="h-full">

<head>
    <style>
        html, body, .layout-with-sidebar, .sidebar-main-content, .sidebar-menu {
            height: 100%;
            min-height: 100vh;
        }
    </style>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=0">
    <meta name="csrf-token" content="<?= e(csrf_token()) ?>">
    <title><?= e(config('app.name', 'PharmaSys')) ?></title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=lexend:400,500,600,700,800&display=swap" rel="stylesheet" />
    <?php echo app(\Illuminate\Foundation\Vite::class)(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>


<body class="app-shell min-h-screen flex h-full <?= e(trim($bodyClass ?? '')) ?>">
    <div class="layout-with-sidebar flex flex-1 min-h-screen h-full">
        <aside class="sidebar-menu flex flex-col min-h-screen h-full bg-white border-r border-gray-100 shadow-sm">
            <div class="px-8 pt-8 pb-6 flex items-center text-2xl font-extrabold text-purple-700 tracking-tight select-none">
                <span class="mr-2">PharmaSys</span>
            </div>
            <ul class="flex-1 flex flex-col gap-2 px-4">
                <li>
                    <a href="<?= e(route('dashboard')) ?>" class="flex items-center gap-4 px-5 py-3 rounded-xl font-semibold text-lg transition <?= request()->routeIs('dashboard') ? 'bg-purple-100 text-purple-700 shadow' : 'text-gray-700 hover:bg-gray-50' ?>">
                        <span class="text-2xl"><i class="fa fa-th-large"></i></span>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="<?= e(route('records.medicines')) ?>" class="flex items-center gap-4 px-5 py-3 rounded-xl font-semibold text-lg transition <?= request()->routeIs('medicines.*') ? 'bg-purple-100 text-purple-700 shadow' : 'text-gray-700 hover:bg-gray-50' ?>">
                        <span class="text-2xl"><i class="fa fa-pills"></i></span>
                        Medicines
                    </a>
                </li>
                <li>
                    <a href="<?= e(route('records.inventory')) ?>" class="flex items-center gap-4 px-5 py-3 rounded-xl font-semibold text-lg transition <?= request()->routeIs('records.inventory') ? 'bg-purple-100 text-purple-700 shadow' : 'text-gray-700 hover:bg-gray-50' ?>">
                        <span class="text-2xl"><i class="fa fa-cubes"></i></span>
                        Inventory
                    </a>
                </li>
                <li>
                    <a href="<?= e(route('records.sales')) ?>" class="flex items-center gap-4 px-5 py-3 rounded-xl font-semibold text-lg transition <?= (request()->routeIs('sales.*') || request()->routeIs('records.sales')) ? 'bg-purple-100 text-purple-700 shadow' : 'text-gray-700 hover:bg-gray-50' ?>">
                        <span class="text-2xl"><i class="fa fa-shopping-cart"></i></span>
                        Sales
                    </a>
                </li>
                <li>
                    <a href="<?= e(route('records.suppliers')) ?>" class="flex items-center gap-4 px-5 py-3 rounded-xl font-semibold text-lg transition <?= (request()->routeIs('suppliers.*') || request()->routeIs('records.suppliers')) ? 'bg-purple-100 text-purple-700 shadow' : 'text-gray-700 hover:bg-gray-50' ?>">
                        <span class="text-2xl"><i class="fa fa-users"></i></span>
                        Suppliers
                    </a>
                </li>
                <li class="mt-auto mb-4"></li>
            </ul>
            <div class="px-8 pb-8">
                <a href="<?= e(route('logout')) ?>" class="w-full flex items-center gap-3 justify-center px-5 py-3 rounded-xl font-bold bg-purple-50 text-purple-700 hover:bg-purple-100 transition text-lg shadow" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <span class="text-2xl"><i class="fa fa-sign-out-alt"></i></span>
                    Logout
                </a>
                <form id="logout-form" action="<?= e(route('logout')) ?>" method="POST" style="display: none;">
                    <?= csrf_field() ?>
                </form>
                <div class="text-xs text-gray-400 text-center mt-6 select-none">Pharmacy Management System</div>
            </div>
        </aside>
        <div class="sidebar-main-content flex-1 flex flex-col min-h-screen h-full">
            <?php echo '<div class="container">'; ?>
                <?php echo '<div class="main-wrapper dashboard-center-content">'; ?>
            <main class="content">
                <?= $slot ?? '' ?>
            </main>
            <footer class="app-footer">
                <p>&copy; 2026 PharmaSys</p>
            </footer>
                <?php echo '</div>'; ?>
            <?php echo '</div>'; ?>
        </div>
    </div>
</body>

</html>
