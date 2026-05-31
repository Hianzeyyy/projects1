<?php
    $type = (string) ($type ?? '');
    $meta = [
        'medicines' => [
            'title' => 'Add New Medicine',
            'subtitle' => 'Create a new medicine record in the inventory',
        ],
        'sales' => [
            'title' => 'Record New Sale',
            'subtitle' => 'Create a new sales transaction record',
        ],
        'suppliers' => [
            'title' => 'Add New Supplier',
            'subtitle' => 'Create a new supplier record in the system',
        ],
    ];

    $typeKey = (string) $type;
    $current = $meta[$typeKey] ?? ['title' => 'Management', 'subtitle' => ''];
?>
<?php ob_start(); ?>
<?php echo '<div>'; ?>
            <h2 class="header-title"><?= e($current['title']) ?></h2>
            <p class="header-subtitle"><?= e($current['subtitle']) ?></p>
        <?php echo '</div>'; ?>
<?php $header = ob_get_clean(); ?>
<?php ob_start(); ?>
<?php echo '<div class="py-12">'; ?>
        <?php echo '<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">'; ?>
            <?php echo '<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">'; ?>
                <?php echo '<div class="p-6">'; ?>
                    <?php if ($__env->exists('management.forms.' . $type)) echo $__env->make('management.forms.' . $type, \Illuminate\Support\Arr::except(get_defined_vars(), ["__data", "__path"]))->render(); ?>
                <?php echo '</div>'; ?>
            <?php echo '</div>'; ?>
        <?php echo '</div>'; ?>
    <?php echo '</div>'; ?>
<?php $slot = ob_get_clean(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ["__data", "__path"]))->render(); ?>
