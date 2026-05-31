<nav class="sidebar-nav">
    <a href="<?= e(route('dashboard')) ?>" class="<?= e(request()->routeIs('dashboard') ? 'active' : '') ?>">
        <span class="sidebar-nav-icon">📊</span>
        <span class="sidebar-nav-text">Dashboard</span>
    </a>
    <a href="<?= e(route('records.medicines')) ?>" class="<?= e(request()->routeIs('medicines.*') ? 'active' : '') ?>">
        <span class="sidebar-nav-icon">💊</span>
        <span class="sidebar-nav-text">Medicines</span>
    </a>
    <a href="<?= e(route('records.inventory')) ?>" class="<?= e(request()->routeIs('records.inventory') ? 'active' : '') ?>">
        <span class="sidebar-nav-icon">📦</span>
        <span class="sidebar-nav-text">Inventory</span>
    </a>
    <a href="<?= e(route('records.sales')) ?>" class="<?= e(request()->routeIs('sales.*') || request()->routeIs('records.sales') ? 'active' : '') ?>">
        <span class="sidebar-nav-icon">💰</span>
        <span class="sidebar-nav-text">Sales</span>
    </a>
    <a href="<?= e(route('records.suppliers')) ?>" class="<?= e(request()->routeIs('suppliers.*') || request()->routeIs('records.suppliers') ? 'active' : '') ?>">
        <span class="sidebar-nav-icon">🤝</span>
        <span class="sidebar-nav-text">Suppliers</span>
    </a>
</nav>
