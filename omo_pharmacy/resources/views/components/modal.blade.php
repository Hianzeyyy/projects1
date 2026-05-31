<?php
$name = $name ?? 'modal';
$show = (bool) ($show ?? false);
$maxWidth = $maxWidth ?? '2xl';
$maxWidthClassMap = [
    'sm' => 'sm:max-w-sm',
    'md' => 'sm:max-w-md',
    'lg' => 'sm:max-w-lg',
    'xl' => 'sm:max-w-xl',
    '2xl' => 'sm:max-w-2xl',
];
$maxWidthClass = $maxWidthClassMap[$maxWidth] ?? 'sm:max-w-2xl';
?>
<?php echo '<div class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50" data-modal-name="' . e($name) . '" style="display: ' . ($show ? 'block' : 'none') . ';">'; ?>
    <?php echo '<div class="fixed inset-0 transform transition-all" onclick="this.parentElement.style.display=\'none\'">'; ?>
        <?php echo '<div class="absolute inset-0 bg-gray-500 opacity-75">'; ?><?php echo '</div>'; ?>
    <?php echo '</div>'; ?>

    <?php echo '<div class="mb-6 bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full ' . e($maxWidthClass) . ' sm:mx-auto">'; ?>
        <?= $slot ?? '' ?>
    <?php echo '</div>'; ?>
<?php echo '</div>'; ?>
