<?php
$attributes = $attributes ?? new \Illuminate\View\ComponentAttributeBag();
$status = strtolower((string) ($status ?? 'safe'));
$title = (string) ($title ?? 'Risk Status');
$message = (string) ($message ?? 'No details provided.');
$acknowledgeId = 'pharmacist_ack_' . uniqid();

$alertClass = 'alert-success';
$label = 'Safe';

if ($status === 'warning') {
    $alertClass = 'alert-warning';
    $label = 'Warning';
}

if ($status === 'critical') {
    $alertClass = 'alert-danger';
    $label = 'Critical / Blocked';
}
?>

<div <?= $attributes->merge(['class' => 'alert ' . $alertClass . ' border mb-3']) ?> role="alert">
    <div class="d-flex align-items-start justify-content-between gap-2">
        <div>
            <h6 class="mb-1"><?= e($title) ?>: <span class="fw-bold"><?= e($label) ?></span></h6>
            <p class="mb-0"><?= e($message) ?></p>
        </div>
    </div>

    <?php if ($status === 'warning'): ?>
        <div class="form-check mt-3">
            <input class="form-check-input" type="checkbox" id="<?= e($acknowledgeId) ?>">
            <label class="form-check-label" for="<?= e($acknowledgeId) ?>">
                Pharmacist Acknowledged
            </label>
        </div>
    <?php endif; ?>

    <?php if ($status === 'critical'): ?>
        <div class="mt-3">
            <button type="button" class="btn btn-danger btn-sm">Manager Override</button>
        </div>
    <?php endif; ?>
</div>
