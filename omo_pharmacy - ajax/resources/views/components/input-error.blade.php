<?php
$attributes = $attributes ?? new \Illuminate\View\ComponentAttributeBag();
$messages = $messages ?? [];
?>
<?php if ($messages): ?>
    <ul <?= $attributes->merge(['class' => 'text-sm text-red-600 space-y-1']) ?>>
        <?php foreach((array) $messages as $message): ?>
            <li><?= $message ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
