<?php
$summaryText = (string) ($summary ?? '');
$alerts = (array) ($alerts ?? []);
?>

<?php if ($summaryText !== ''): ?>
    <p class="mb-3"><?= e($summaryText) ?></p>
<?php endif; ?>

<?= $alerts['allergy_match'] ?? '' ?>
<?= $alerts['drug_interaction'] ?? '' ?>
<?= $alerts['dosage_safety'] ?? '' ?>
