<?php
$status = $status ?? null;
?>
<?php if ($status): ?>
    <?php echo '<div class="font-medium text-sm text-violet-600">'; ?>
        <?= e($status) ?>
    <?php echo '</div>'; ?>
<?php endif; ?>
