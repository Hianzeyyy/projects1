<?php
    $type = (string) ($type ?? '');
    $bodyClass = 'records-page';
    $meta = [
        'medicines' => [
            'title' => 'Medicines',
            'subtitle' => 'Manage your medicine catalog',
            'button_label' => 'Add Medicine',
            'button_route' => 'medicines.create',
            'view' => 'records.partials.table-medicines',
        ],
        'inventory' => [
            'title' => 'Inventory',
            'subtitle' => 'Manage stock levels and batches',
            'button_label' => 'Add Stock',
            'button_route' => 'medicines.create',
            'view' => 'records.partials.table-inventory',
        ],
        'sales' => [
            'title' => 'Sales',
            'subtitle' => 'Track and manage sales transactions',
            'button_label' => '➕ New Sale',
            'button_route' => 'sales.create',
            'view' => 'records.partials.table-sales',
        ],
        'suppliers' => [
            'title' => 'Suppliers',
            'subtitle' => 'Manage supplier contacts and information',
            'button_label' => '➕ Add Supplier',
            'button_route' => 'suppliers.create',
            'view' => 'records.partials.suppliers',
        ],
    ];

    $typeKey = (string) $type;
    $current = $meta[$typeKey] ?? [
        'title' => 'Records',
        'subtitle' => 'Manage records',
        'button_label' => '➕ Add',
        'button_route' => 'dashboard',
        'view' => null,
    ];
?>
<?php ob_start(); ?>
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
    <div>
        <h2 class="text-3xl font-extrabold text-purple-700 mb-1"><?= e($current['title']) ?></h2>
        <p class="text-base text-gray-500 mb-2"><?= e($current['subtitle']) ?></p>
    </div>
    <div>
        <a href="<?= e(route($current['button_route'])) ?>" class="inline-flex items-center gap-2 px-6 py-2 rounded-full bg-purple-50 text-purple-700 font-semibold hover:bg-purple-100 transition text-base shadow">
            <i class="fa fa-plus"></i> <?= e($current['button_label']) ?>
        </a>
    </div>
</div>
<?php $header = ob_get_clean(); ?>
<?php ob_start(); ?>
<?php echo '<div class="records-page-shell records-page-' . e($typeKey) . '">'; ?>

    <?php if (session('success')): ?>
        <div class="mb-4 p-4 rounded-lg bg-green-50 text-green-700 font-semibold flex items-center gap-2 shadow">
            <i class="fa fa-check-circle"></i> <?= e(session('success')) ?>
        </div>
    <?php endif; ?>

    <?php if ($type === 'inventory'): ?>
        <?php
            $lowStockCount = collect($inventory ?? [])->filter(function ($item) {
                return $item->quantity <= $item->reorder_level;
            })->count();
        ?>
        <?php if ($lowStockCount > 0): ?>
            <div class="mb-4 p-4 rounded-lg bg-red-50 text-red-700 font-semibold flex items-center gap-2 shadow">
                <i class="fa fa-exclamation-triangle"></i>
                <?= e($lowStockCount) ?> <?= e($lowStockCount == 1 ? 'item needs' : 'items need') ?> restocking
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <?php if ($type === 'sales'): ?>
        <?php
            $totalRevenue = $sales->sum('total_amount');
            $totalTransactions = $sales->count();
        ?>
        <div class="mb-4 p-6 rounded-2xl bg-purple-50 flex flex-col md:flex-row md:items-center md:justify-between gap-4 shadow">
            <div>
                <div class="text-xs text-gray-400 mb-1">Total Revenue</div>
                <div class="text-2xl font-bold text-purple-700 mb-1">₱<?= e(number_format($totalRevenue, 2)) ?></div>
                <div class="text-sm text-gray-500"><?= e($totalTransactions) ?> <?= e($totalTransactions == 1 ? 'transaction' : 'transactions') ?></div>
            </div>
            <div class="text-4xl text-purple-300"><i class="fa fa-calendar-alt"></i></div>
        </div>
    <?php endif; ?>

    <div class="mb-6 flex items-center gap-2 bg-gray-50 rounded-lg px-4 py-2 shadow max-w-md">
        <span class="text-gray-400 text-lg"><i class="fa fa-search"></i></span>
        <input type="text" placeholder="Search <?= e($type) ?>..." class="flex-1 bg-transparent outline-none text-gray-700 placeholder-gray-400 py-2">
    </div>

    <?php if ($current['view']): ?>
        <?php echo $__env->make($current['view'], \Illuminate\Support\Arr::except(get_defined_vars(), ["__data", "__path"]))->render(); ?>
    <?php endif; ?>
</div>
<?php $slot = ob_get_clean(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ["__data", "__path"]))->render(); ?>
