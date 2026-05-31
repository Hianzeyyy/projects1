@extends('app')

@section('header')
    <h2 class="header-title">Welcome to PharmaSys</h2>
    <p class="header-subtitle">Overview of your pharmacy management system</p>
@endsection

@section('content')
<div style="display: flex; gap: 2rem; flex-wrap: wrap; margin-bottom: 2.5rem;">
    <div style="background: #fff; border-radius: 1.5rem; box-shadow: 0 2px 12px 0 rgba(16,185,129,0.08); padding: 2rem 2.5rem; min-width: 220px; flex: 1 1 220px; max-width: 260px;">
        <div style="font-size: 1.1rem; color: #16a34a; font-weight: 700;">Total Medicines 3</div>
    </div>
    <div style="background: #fff; border-radius: 1.5rem; box-shadow: 0 2px 12px 0 rgba(16,185,129,0.08); padding: 2rem 2.5rem; min-width: 220px; flex: 1 1 220px; max-width: 260px;">
        <div style="font-size: 1.1rem; color: #0284c7; font-weight: 700;">Inventory Items 3</div>
    </div>
    <div style="background: #fff; border-radius: 1.5rem; box-shadow: 0 2px 12px 0 rgba(16,185,129,0.08); padding: 2rem 2.5rem; min-width: 220px; flex: 1 1 220px; max-width: 260px;">
        <div style="font-size: 1.1rem; color: #0e7490; font-weight: 700;">Total Sales 2</div>
    </div>
    <div style="background: #fff; border-radius: 1.5rem; box-shadow: 0 2px 12px 0 rgba(16,185,129,0.08); padding: 2rem 2.5rem; min-width: 220px; flex: 1 1 220px; max-width: 260px;">
        <div style="font-size: 1.1rem; color: #155e75; font-weight: 700;">Suppliers 2</div>
    </div>
</div>

<div style="display: flex; gap: 2rem; flex-wrap: wrap; margin-bottom: 2.5rem;">
    <div style="background: #fef9c3; border-radius: 1.5rem; box-shadow: 0 2px 12px 0 rgba(251,191,36,0.08); padding: 2rem 2.5rem; min-width: 260px; flex: 1 1 260px; max-width: 320px;">
        <div style="font-size: 1.1rem; color: #b45309; font-weight: 700; margin-bottom: 0.5rem;">Low Stock Alert</div>
        <div style="font-size: 1.5rem; font-weight: 700;">Items Below Reorder Level 1</div>
        <a href="/inventory" style="margin-top: 1rem; color: #0284c7; font-weight: 600; text-decoration: underline;">View Inventory</a>
    </div>
    <div style="background: #e0f2fe; border-radius: 1.5rem; box-shadow: 0 2px 12px 0 rgba(14,116,144,0.08); padding: 2rem 2.5rem; min-width: 260px; flex: 1 1 260px; max-width: 320px;">
        <div style="font-size: 1.1rem; color: #0e7490; font-weight: 700; margin-bottom: 0.5rem;">Sales Summary</div>
        <div style="font-size: 1.5rem; font-weight: 700;">Total Revenue $20.73</div>
        <a href="/sales" style="margin-top: 1rem; color: #16a34a; font-weight: 600; text-decoration: underline;">View Sales</a>
    </div>
</div>

<div style="margin-bottom: 2.5rem;">
    <div style="font-size: 1.1rem; font-weight: 700; margin-bottom: 1rem;">Quick Actions</div>
    <div style="display: flex; gap: 1.5rem; flex-wrap: wrap;">
        <a href="/medicines/create" class="btn btn-primary">Add Medicine</a>
        <a href="/inventory" class="btn btn-primary">Update Stock</a>
        <a href="/sales/create" class="btn btn-primary">New Sale</a>
        <a href="/suppliers/create" class="btn btn-primary">Add Supplier</a>
    </div>
</div>
@endsection


<?php
$bodyClass = 'dashboard-page';
$medicinesCount = $medicinesCount ?? 0;
$salesCount = $salesCount ?? 0;
$totalSales = $totalSales ?? 0;
$suppliersCount = $suppliersCount ?? 0;
$todaySalesCount = $todaySalesCount ?? 0;
$todaySalesTotal = $todaySalesTotal ?? 0;
$outOfStockCount = $outOfStockCount ?? 0;
$lowStockCount = $lowStockCount ?? 0;
$healthyStockCount = $healthyStockCount ?? 0;
$suppliersWithContact = $suppliersWithContact ?? 0;
$averageSale = $averageSale ?? 0;
$stockHealth = $stockHealth ?? 0;
$activeAlerts = $activeAlerts ?? 0;
$lowStockMedicines = collect($lowStockMedicines ?? []);
$recentSales = collect($recentSales ?? []);
$topMedicines = collect($topMedicines ?? []);
$latestSuppliers = collect($latestSuppliers ?? []);
?>
<?php ob_start(); ?>
<?php echo '<div>'; ?>
    <h2 class="header-title">Pharmacy</h2>
    <p class="header-subtitle">Redesigned view of your phamarcy control flow</p>
<?php echo '</div>'; ?>
<?php $header = ob_get_clean(); ?>

<?php ob_start(); ?>
<section class="hero-shell">
    <div>
        <h1 class="hero-title">Pharmacy</h1>
        <p class="hero-subtitle">A purpose-built workspace for monitoring stock flow, managing sales activities, and evaluating supplier readiness.</p>
    </div>
</section>

<h2 class="section-title">Core Performance</h2>
<section class="stats-grid">
    <article class="stat-card">
        <?php echo '<div>'; ?>
            <?php echo '<div class="stat-title">'; ?>Medicine Catalog<?php echo '</div>'; ?>
            <?php echo '<div class="stat-value">'; ?><?= e($medicinesCount) ?><?php echo '</div>'; ?>
        <?php echo '</div>'; ?>
    </article>

    <article class="stat-card">
        <?php echo '<div>'; ?>
            <?php echo '<div class="stat-title">'; ?>Completed Sales<?php echo '</div>'; ?>
            <?php echo '<div class="stat-value">'; ?><?= e($salesCount) ?><?php echo '</div>'; ?>
        <?php echo '</div>'; ?>
    </article>

    <article class="stat-card">
        <?php echo '<div>'; ?>
            <?php echo '<div class="stat-title">'; ?>Supplier Network<?php echo '</div>'; ?>
            <?php echo '<div class="stat-value">'; ?><?= e($suppliersCount) ?><?php echo '</div>'; ?>
        <?php echo '</div>'; ?>
    </article>

    <article class="stat-card">
        <?php echo '<div>'; ?>
            <?php echo '<div class="stat-title">'; ?>Revenue Total<?php echo '</div>'; ?>
            <?php echo '<div class="stat-value">'; ?>PHP <?= e(number_format($totalSales, 2)) ?><?php echo '</div>'; ?>
        <?php echo '</div>'; ?>
    </article>
</section>

<h2 class="section-title">Operational Signals</h2>
<section class="snapshot-row">
    <?php echo '<div class="snapshot-item">'; ?>Out-of-Stock: <span class="snapshot-value"><?= e($outOfStockCount) ?></span><?php echo '</div>'; ?>
    <?php echo '<div class="snapshot-item">'; ?>Healthy Stock: <span class="snapshot-value"><?= e($healthyStockCount) ?></span><?php echo '</div>'; ?>
    <?php echo '<div class="snapshot-item">'; ?>Suppliers With Contact: <span class="snapshot-value"><?= e($suppliersWithContact) ?></span><?php echo '</div>'; ?>
    <?php echo '<div class="snapshot-item">'; ?>Average Sale: <span class="snapshot-value">PHP <?= e(number_format($averageSale, 2)) ?></span><?php echo '</div>'; ?>
</section>

<h2 class="section-title">Action Rooms</h2>
<section class="module-section">
    <?php echo '<div class="module-grid">'; ?>
        <article class="module-card">
            <h4>Management Entry Forms</h4>
            <p>Register new medicines, suppliers, and sales activity with dedicated forms.</p>
            <?php echo '<div class="module-links">'; ?>
                <a href="<?= e(route('medicines.create')) ?>" class="action-btn">New Medicine</a>
                <a href="<?= e(route('suppliers.create')) ?>" class="action-btn">New Supplier</a>
                <a href="<?= e(route('sales.create')) ?>" class="action-btn">New Sale</a>
            <?php echo '</div>'; ?>
        </article>

        <article class="module-card">
            <h4>Records and Maintenance</h4>
            <p>Review records and perform view, edit, update, or delete actions across modules.</p>
            <?php echo '<div class="module-links">'; ?>
                <a href="<?= e(route('records.medicines')) ?>" class="action-btn">Medicine Records</a>
                <a href="<?= e(route('records.suppliers')) ?>" class="action-btn">Supplier Records</a>
                <a href="<?= e(route('records.sales')) ?>" class="action-btn">Sales Records</a>
            <?php echo '</div>'; ?>
        </article>

        <article class="module-card">
            <h4>Fast Navigation</h4>
            <p>Jump directly to the right area based on your daily workflow.</p>
            <?php echo '<div class="module-links">'; ?>
                <a href="<?= e(route('dashboard')) ?>" class="action-btn">Dashboard Home</a>
                <a href="<?= e(route('records.medicines')) ?>" class="action-btn">Stock Monitor</a>
                <a href="<?= e(route('records.sales')) ?>" class="action-btn">Sales Review</a>
            <?php echo '</div>'; ?>
        </article>
    <?php echo '</div>'; ?>
</section>

<h2 class="section-title">Performance Streams</h2>
<section class="panel-grid">
    <article class="panel">
        <h3 class="panel-title">Inventory Risk Panel</h3>
        <?php echo '<div class="panel-row">'; ?>
            <span>Items below reorder level</span>
            <span class="panel-value-danger"><?= e($lowStockCount) ?></span>
        <?php echo '</div>'; ?>
        <a href="<?= e(route('records.medicines')) ?>" class="panel-btn">Inspect Inventory</a>
    </article>

    <article class="panel">
        <h3 class="panel-title">Revenue Pulse</h3>
        <?php echo '<div class="panel-row">'; ?>
            <span>Total revenue captured</span>
            <span class="panel-value-success">PHP <?= e(number_format($totalSales, 2)) ?></span>
        <?php echo '</div>'; ?>
        <a href="<?= e(route('records.sales')) ?>" class="panel-btn">Open Sales Ledger</a>
    </article>

    <article class="panel">
        <h3 class="panel-title">Average Ticket View</h3>
        <?php echo '<div class="panel-row">'; ?>
            <span>Average amount per sale</span>
            <span class="panel-value-success">PHP <?= e(number_format($averageSale, 2)) ?></span>
        <?php echo '</div>'; ?>
        <a href="<?= e(route('records.sales')) ?>" class="panel-btn">View Detailed Sales</a>
    </article>
</section>

<h2 class="section-title">Live Feeds</h2>
<section class="insights-grid">
    <article class="insight-card">
        <h3 class="insight-title">Low-Stock Medicines</h3>
        <?php if ($lowStockMedicines->isEmpty()): ?>
            <?php echo '<div class="empty-note">'; ?>No medicines are currently below reorder level. Inventory is stable.<?php echo '</div>'; ?>
        <?php else: ?>
            <ul class="insight-list">
                <?php foreach ($lowStockMedicines as $medicineRow): ?>
                    <?php
                    $medicineName = (string) data_get($medicineRow, 'medicine_name', 'Medicine');
                    $reorderLevel = (string) data_get($medicineRow, 'reorder_level', '0');
                    $medicineQty = (string) data_get($medicineRow, 'quantity', '0');
                    ?>
                    <li class="insight-item">
                        <?php echo '<div>'; ?>
                            <strong><?= e($medicineName) ?></strong>
                            <?php echo '<div class="insight-meta">'; ?>Reorder threshold: <?= e($reorderLevel) ?> units<?php echo '</div>'; ?>
                        <?php echo '</div>'; ?>
                        <span class="insight-badge insight-badge-danger"><?= e($medicineQty) ?> left</span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </article>

    <article class="insight-card">
        <h3 class="insight-title">Recent Sales Log</h3>
        <?php if ($recentSales->isEmpty()): ?>
            <?php echo '<div class="empty-note">'; ?>No sales have been recorded yet. Create a sale to start this stream.<?php echo '</div>'; ?>
        <?php else: ?>
            <ul class="insight-list">
                <?php foreach ($recentSales as $saleRow): ?>
                    <?php
                    $saleMedicineName = (string) data_get($saleRow, 'medicine_name', 'Medicine');
                    $saleCreatedAtRaw = data_get($saleRow, 'created_at');
                    $saleDateLabel = $saleCreatedAtRaw ? \Carbon\Carbon::parse($saleCreatedAtRaw)->format('M d, Y') : 'N/A';
                    $saleTotalAmount = (float) data_get($saleRow, 'total_amount', 0);
                    ?>
                    <li class="insight-item">
                        <?php echo '<div>'; ?>
                            <strong><?= e($saleMedicineName) ?></strong>
                            <?php echo '<div class="insight-meta">'; ?>Logged on <?= e($saleDateLabel) ?><?php echo '</div>'; ?>
                        <?php echo '</div>'; ?>
                        <span class="insight-badge insight-badge-info">PHP <?= e(number_format($saleTotalAmount, 2)) ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </article>
</section>

<h2 class="section-title">Quick Command Bar</h2>
<section class="quick-actions">
    <h3 class="quick-actions-title">Choose Next Action</h3>
    <?php echo '<div class="actions-grid">'; ?>
        <a href="<?= e(route('medicines.create')) ?>" class="action-btn">Register Medicine</a>
        <a href="<?= e(route('records.medicines')) ?>" class="action-btn">Open Inventory Records</a>
        <a href="<?= e(route('sales.create')) ?>" class="action-btn">Record New Sale</a>
        <a href="<?= e(route('suppliers.create')) ?>" class="action-btn">Register Supplier</a>
    <?php echo '</div>'; ?>
</section>

<?php $slot = ob_get_clean(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
