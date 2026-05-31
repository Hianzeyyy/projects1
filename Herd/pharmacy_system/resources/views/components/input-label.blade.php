<?php
$attributes = $attributes ?? new \Illuminate\View\ComponentAttributeBag();
$value = $value ?? null;
$slot = $slot ?? '';
?>
<label <?= $attributes->merge(['class' => 'block font-medium text-sm text-gray-700']) ?>>
    <?= $value ?? $slot ?>
</label>
