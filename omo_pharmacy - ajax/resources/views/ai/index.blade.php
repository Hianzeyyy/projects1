<?php
$bodyClass = 'records-page';
$input = $input ?? [];
$result = $result ?? null;

ob_start();
?>
<?php echo '<div>'; ?>
    <h2 class="header-title">AI Process</h2>
    <p class="header-subtitle">Use AI-assisted matching to suggest medicine options from your inventory</p>
<?php echo '</div>'; ?>
<?php echo '<div class="header-actions">'; ?>
    <a href="<?= e(route('dashboard')) ?>" class="btn btn-primary">Back to Dashboard</a>
<?php echo '</div>'; ?>
<?php
$header = ob_get_clean();

ob_start();
?>
<?php echo '<div class="records-page-shell" id="ai-process-shell">'; ?>
    <div id="ai-ajax-success" class="auth-success" style="margin-bottom:12px; display:none;"></div>
    <div id="ai-ajax-error" class="auth-error" style="margin-bottom:12px; display:none;"></div>

    <?php if ($errors->any()): ?>
        <div class="auth-error" style="margin-bottom:12px;">
            <strong>Please review the form.</strong>
            <ul style="margin:8px 0 0 18px;">
                <?php foreach ($errors->all() as $error): ?>
                    <li><?= e((string) $error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php echo '<div class="module-card" style="margin-bottom:1rem;">'; ?>
        <p style="margin:0;"><strong>How to use:</strong> Enter patient details, list symptoms using clear keywords, then run AI Process. Review risk flags before final dispensing.</p>
    <?php echo '</div>'; ?>

    <form method="POST" action="<?= e(route('ai.process', [], false)) ?>" id="ai-process-form">
        <?= csrf_field() ?>

        <?php echo '<div class="module-grid">'; ?>
            <article class="module-card">
                <label for="patient_name"><strong>Patient Name</strong></label>
                <input id="patient_name" name="patient_name" type="text" value="<?= e((string) data_get($input, 'patient_name', '')) ?>" class="records-search-input" required>
            </article>

            <article class="module-card">
                <label for="age"><strong>Age</strong></label>
                <input id="age" name="age" type="number" min="0" max="120" value="<?= e((string) data_get($input, 'age', '')) ?>" class="records-search-input" required>
            </article>

            <article class="module-card">
                <label for="allergies"><strong>Allergies</strong></label>
                <input id="allergies" name="allergies" type="text" value="<?= e((string) data_get($input, 'allergies', '')) ?>" class="records-search-input">
            </article>
        <?php echo '</div>'; ?>

        <?php echo '<div class="module-card" style="margin-top:1rem;">'; ?>
            <label for="symptoms"><strong>Symptoms</strong></label>
            <p style="margin:0 0 8px 0; color:#5e7088;">Tip: Use symptom keywords separated by spaces or commas (example: fever cough headache).</p>
            <div class="ai-chip-row" style="margin-bottom:0.6rem;">
                <button type="button" class="btn btn-secondary ai-chip" data-ai-symptom="fever">+ fever</button>
                <button type="button" class="btn btn-secondary ai-chip" data-ai-symptom="cough">+ cough</button>
                <button type="button" class="btn btn-secondary ai-chip" data-ai-symptom="headache">+ headache</button>
                <button type="button" class="btn btn-secondary ai-chip" data-ai-symptom="allergy">+ allergy</button>
                <button type="button" class="btn btn-secondary ai-chip" data-ai-symptom="sore throat">+ sore throat</button>
            </div>
            <textarea id="symptoms" name="symptoms" rows="4" class="records-search-input" required><?= e((string) data_get($input, 'symptoms', '')) ?></textarea>
            <small id="symptoms-counter" style="display:block; margin-top:6px; color:#5e7088;">0 / 1000</small>
        <?php echo '</div>'; ?>

        <?php echo '<div class="module-card" style="margin-top:1rem;">'; ?>
            <label for="notes"><strong>Clinical Notes (Optional)</strong></label>
            <textarea id="notes" name="notes" rows="3" class="records-search-input"><?= e((string) data_get($input, 'notes', '')) ?></textarea>
        <?php echo '</div>'; ?>

        <div style="margin-top:1rem;">
            <button type="submit" class="btn btn-primary">Run AI Process</button>
            <button type="reset" class="btn btn-secondary" style="margin-left:0.5rem;">Clear Form</button>
        </div>
    </form>

    <div id="ai-result-container">
    <?php if ($result): ?>
        <h3 class="section-title" style="margin-top:1rem;">AI Output</h3>
        <?php echo '<div class="module-card">'; ?>
            <p><strong>Patient:</strong> <?= e((string) data_get($result, 'patient', '')) ?></p>
            <p><strong>Age:</strong> <?= e((string) data_get($result, 'age', '')) ?></p>
            <p><strong>Analyzed Medicines:</strong> <?= e((string) data_get($result, 'summary.candidate_count', 0)) ?> | <strong>Matches Found:</strong> <?= e((string) data_get($result, 'summary.match_count', 0)) ?></p>
            <p><strong>Detected Symptom Terms:</strong> <?= e(implode(', ', (array) data_get($result, 'summary.symptom_terms', []))) ?: 'None' ?></p>
            <p><strong>Disclaimer:</strong> <?= e((string) data_get($result, 'disclaimer', '')) ?></p>

            <h4 style="margin-top:0.75rem;">Recommended Medicines</h4>
            <?php $recommendations = collect(data_get($result, 'recommendations', [])); ?>
            <?php if ($recommendations->isEmpty()): ?>
                <p>No direct match found based on current symptom keywords. Review manually.</p>
                <?php $tips = collect(data_get($result, 'no_match_tips', [])); ?>
                <?php if ($tips->isNotEmpty()): ?>
                    <ul>
                        <?php foreach ($tips as $tip): ?>
                            <li><?= e((string) $tip) ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            <?php else: ?>
                <?php echo '<div class="data-table">'; ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Confidence</th>
                                <th>Medicine</th>
                                <th>Stock</th>
                                <th>Price</th>
                                <th>Match Terms</th>
                                <th>Reason</th>
                                <th>Dosage Reminder</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($recommendations as $item): ?>
                            <tr>
                                <td><?= e((string) data_get($item, 'confidence', 'Low')) ?></td>
                                <td><?= e((string) data_get($item, 'name', '')) ?></td>
                                <td><?= e((string) data_get($item, 'stock', '')) ?></td>
                                <td>PHP <?= e((string) data_get($item, 'price', '0.00')) ?></td>
                                <td><?= e((string) data_get($item, 'match_terms', '-')) ?></td>
                                <td><?= e((string) data_get($item, 'reason', '')) ?></td>
                                <td><?= e((string) data_get($item, 'dosage_reminder', '')) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php echo '</div>'; ?>
            <?php endif; ?>

            <?php $riskFlags = collect(data_get($result, 'risk_flags', [])); ?>
            <?php if ($riskFlags->isNotEmpty()): ?>
                <h4 style="margin-top:0.75rem;">Risk Flags</h4>
                <ul>
                    <?php foreach ($riskFlags as $flag): ?>
                        <li><?= e((string) $flag) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <?php $careSteps = collect(data_get($result, 'care_steps', [])); ?>
            <?php if ($careSteps->isNotEmpty()): ?>
                <h4 style="margin-top:0.75rem;">Next Best Actions</h4>
                <ol>
                    <?php foreach ($careSteps as $step): ?>
                        <li><?= e((string) $step) ?></li>
                    <?php endforeach; ?>
                </ol>
            <?php endif; ?>
        <?php echo '</div>'; ?>
    <?php endif; ?>
    </div>
<?php echo '</div>'; ?>
<?php
$slot = ob_get_clean();
echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render();
