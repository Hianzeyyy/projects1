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
<div class="mb-10">
    <h2 class="text-4xl font-black text-purple-700 mb-1 tracking-tight">Welcome to PharmaSys</h2>
    <p class="text-lg text-gray-400 font-medium">Overview of your pharmacy management system</p>
</div>

<section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-10">
    <div class="bg-white rounded-3xl shadow-md p-8 flex flex-col items-center border border-purple-100 min-w-[220px]">
        <div class="text-xs text-gray-400 mb-3 font-semibold uppercase tracking-wider">Total Medicines</div>
        <div class="flex items-center gap-3">
            <span class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-purple-50 text-purple-500 text-3xl"><i class="fa fa-pills"></i></span>
            <span class="text-4xl font-extrabold text-gray-900"><?= e($medicinesCount) ?></span>
        </div>
    </div>
    <div class="bg-white rounded-3xl shadow-md p-8 flex flex-col items-center border border-purple-100 min-w-[220px]">
        <div class="text-xs text-gray-400 mb-3 font-semibold uppercase tracking-wider">Inventory Items</div>
        <div class="flex items-center gap-3">
            <span class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-green-50 text-green-500 text-3xl"><i class="fa fa-cubes"></i></span>
            <span class="text-4xl font-extrabold text-gray-900"><?= e($healthyStockCount + $lowStockCount + $outOfStockCount) ?></span>
        </div>
    </div>
    <div class="bg-white rounded-3xl shadow-md p-8 flex flex-col items-center border border-purple-100 min-w-[220px]">
        <div class="text-xs text-gray-400 mb-3 font-semibold uppercase tracking-wider">Total Sales</div>
        <div class="flex items-center gap-3">
            <span class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-pink-50 text-pink-500 text-3xl"><i class="fa fa-shopping-cart"></i></span>
            <span class="text-4xl font-extrabold text-gray-900"><?= e($salesCount) ?></span>
        </div>
    </div>
    <div class="bg-white rounded-3xl shadow-md p-8 flex flex-col items-center border border-purple-100 min-w-[220px]">
        <div class="text-xs text-gray-400 mb-3 font-semibold uppercase tracking-wider">Suppliers</div>
        <div class="flex items-center gap-3">
            <span class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-orange-50 text-orange-500 text-3xl"><i class="fa fa-users"></i></span>
            <span class="text-4xl font-extrabold text-gray-900"><?= e($suppliersCount) ?></span>
        </div>
    </div>
</section>

<div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
    <div class="bg-white rounded-3xl shadow-md p-8 border border-red-100 flex flex-col justify-between min-h-[200px]">
        <div class="flex items-center gap-3 mb-3 text-red-600 font-bold text-xl">
            <i class="fa fa-exclamation-triangle"></i> Low Stock Alert
        </div>
        <div class="text-gray-500 mb-2 font-medium">Items Below Reorder Level</div>
        <div class="flex items-center gap-2 mb-6">
            <span class="text-3xl font-extrabold text-red-600"><?= e($lowStockCount) ?></span>
        </div>
        <a href="<?= e(route('records.inventory')) ?>" class="block w-full text-center py-3 rounded-xl bg-purple-50 text-purple-700 font-bold hover:bg-purple-100 transition text-lg">View Inventory &rarr;</a>
    </div>
    <div class="bg-white rounded-3xl shadow-md p-8 border border-green-100 flex flex-col justify-between min-h-[200px]">
        <div class="flex items-center gap-3 mb-3 text-green-600 font-bold text-xl">
            <i class="fa fa-chart-line"></i> Sales Summary
        </div>
        <div class="text-gray-500 mb-2 font-medium">Total Revenue</div>
        <div class="flex items-center gap-2 mb-6">
            <span class="text-3xl font-extrabold text-green-600">$<?= e(number_format($totalSales, 2)) ?></span>
        </div>
        <a href="<?= e(route('records.sales')) ?>" class="block w-full text-center py-3 rounded-xl bg-purple-50 text-purple-700 font-bold hover:bg-purple-100 transition text-lg">View Sales &rarr;</a>
    </div>
</div>

<div class="bg-white rounded-3xl shadow-md p-8 border border-purple-100 mb-10">
    <div class="font-bold text-xl mb-6 text-gray-800">Quick Actions</div>
    <div class="flex flex-wrap gap-6">
        <a href="<?= e(route('records.medicines')) ?>" class="inline-flex items-center gap-2 px-8 py-3 rounded-full bg-purple-50 text-purple-700 font-bold hover:bg-purple-100 transition text-lg shadow"><i class="fa fa-pills"></i> Add Medicine</a>
        <a href="<?= e(route('records.inventory')) ?>" class="inline-flex items-center gap-2 px-8 py-3 rounded-full bg-green-50 text-green-700 font-bold hover:bg-green-100 transition text-lg shadow"><i class="fa fa-cubes"></i> Update Stock</a>
        <a href="<?= e(route('records.sales')) ?>" class="inline-flex items-center gap-2 px-8 py-3 rounded-full bg-pink-50 text-pink-700 font-bold hover:bg-pink-100 transition text-lg shadow"><i class="fa fa-shopping-cart"></i> New Sale</a>
        <a href="<?= e(route('records.suppliers')) ?>" class="inline-flex items-center gap-2 px-8 py-3 rounded-full bg-orange-50 text-orange-700 font-bold hover:bg-orange-100 transition text-lg shadow"><i class="fa fa-users"></i> Add Supplier</a>
    </div>
</div>
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
    <?php echo '</div>'; ?>
</section>

<?php $slot = ob_get_clean(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


