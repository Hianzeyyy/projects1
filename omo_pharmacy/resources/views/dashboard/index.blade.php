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
@section('content')
<div class="container">
    <h1>Reporting Dashboard</h1>
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>Daily Sales</h5>
                    <p>{{ $todaySalesTotal ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>Weekly Revenue</h5>
                    <p>{{ $weeklyRevenue ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>Monthly Profit Margin</h5>
                    <p>{{ $monthlyProfit ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>Low Stock Alerts</h5>
                    <ul>
                        @foreach($lowStockMedicines as $med)
                            <li>{{ $med['medicine_name'] }} ({{ $med['quantity'] }})</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <form method="POST" action="{{ route('reports.download') }}" class="mt-4">
        @csrf
        <div class="form-group">
            <label for="type">Report Type</label>
            <select name="type" id="type" class="form-control">
                <option value="daily">Daily</option>
                <option value="weekly">Weekly</option>
                <option value="monthly">Monthly</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary mt-2">Download Summary Report (PDF)</button>
    </form>
    <div class="mt-4" style="display:flex; gap:0.75rem; flex-wrap:wrap;">
        <a href="<?= e(route('reports.index')) ?>" class="btn btn-primary">View Reports</a>
        <a href="<?= e(route('ai.index')) ?>" class="btn btn-primary">Open AI Process</a>
    </div>
</div>
@endsection
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
