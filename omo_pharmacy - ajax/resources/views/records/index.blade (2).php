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
<?php echo '<div>'; ?>
            <h2 class="header-title"><?= e($current['title']) ?></h2>
            <p class="header-subtitle"><?= e($current['subtitle']) ?></p>
        <?php echo '</div>'; ?>
        <?php echo '<div class="header-actions">'; ?>
            <a href="<?= e(route($current['button_route'])) ?>" class="btn btn-primary"><?= e($current['button_label']) ?></a>
        <?php echo '</div>'; ?>
<?php $header = ob_get_clean(); ?>
<?php ob_start(); ?>
<?php echo '<div class="records-page-shell records-page-' . e($typeKey) . '">'; ?>
    <?php if (session('success')): ?>
        <?php echo '<div class="success-alert">'; ?>✓ <?= e(session('success')) ?><?php echo '</div>'; ?>
    <?php endif; ?>

    <?php if ($type === 'inventory'): ?>
        <?php
            $lowStockCount = collect($inventory ?? [])->filter(function ($item) {
                return $item->quantity <= $item->reorder_level;
            })->count();
        ?>

        <?php if ($lowStockCount > 0): ?>
            <?php echo '<div class="records-warning-alert">'; ?>
                <span class="records-warning-icon">&#9888;</span>
                <span><?= e($lowStockCount) ?> <?= e($lowStockCount == 1 ? 'item needs' : 'items need') ?> restocking</span>
            <?php echo '</div>'; ?>
        <?php endif; ?>
    <?php endif; ?>

    <?php if ($type === 'sales'): ?>
        <?php
            $totalRevenue = $sales->sum('total_amount');
            $totalTransactions = $sales->count();
        ?>

        <?php echo '<div class="records-sales-hero">'; ?>
            <?php echo '<div>'; ?>
                <p class="records-sales-subtitle">Total Revenue</p>
                <h2 class="records-sales-total">₱<?= e(number_format($totalRevenue, 2)) ?></h2>
                <p class="records-sales-transactions"><?= e($totalTransactions) ?> <?= e($totalTransactions == 1 ? 'transaction' : 'transactions') ?></p>
            <?php echo '</div>'; ?>
            <?php echo '<div class="records-sales-icon">'; ?>📅<?php echo '</div>'; ?>
        <?php echo '</div>'; ?>
    <?php endif; ?>

    <?php echo '<div class="records-search-wrap">'; ?>
        <?php echo '<div class="records-search-box">'; ?>
            <span class="records-search-icon" aria-hidden="true">&#128269;</span>
            <input
                type="text"
                placeholder="Search <?= e($type) ?>..."
                class="records-search-input"
                <?= $type === 'inventory' ? 'id="inventory-search" data-inventory-search="1" autocomplete="off"' : '' ?>
            >
        <?php echo '</div>'; ?>
    <?php echo '</div>'; ?>

    <?php if ($type === 'inventory'): ?>
        <div id="inventory-loading" class="alert alert-info" style="display:none;">Loading inventory...</div>
    <?php endif; ?>

    <div <?= $type === 'inventory' ? 'id="inventory-table"' : '' ?>>
        <?php if ($current['view']): ?>
            <?php echo $__env->make($current['view'], \Illuminate\Support\Arr::except(get_defined_vars(), ["__data", "__path"]))->render(); ?>
        <?php endif; ?>
    </div>
<?php echo '</div>'; ?>
<?php $slot = ob_get_clean(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ["__data", "__path"]))->render(); ?>
