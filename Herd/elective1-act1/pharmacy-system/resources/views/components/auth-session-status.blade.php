<!-- Props: 'status' -->

<?php if($status): ?>
    <div <?= $attributes->merge(['class' => 'font-medium text-sm text-green-600']) ?>>
        <?= $status ?>
    </div>
<?php endif; ?>
