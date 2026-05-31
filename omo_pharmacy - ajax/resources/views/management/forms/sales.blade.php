<form action="<?= e(route('sales.store', [], false)) ?>" method="POST" id="sale-form" data-risk-endpoint="<?= e(route('sales.risk-assess', [], false)) ?>">
    <?= csrf_field() ?>

    <div id="sale-ajax-success" class="alert alert-success" style="display:none;"></div>
    <div id="sale-ajax-error" class="alert alert-danger" style="display:none;"></div>

    <?php $medicineOptions = collect($medicines ?? []); ?>
    <?php $profileOptions = collect($profiles ?? []); ?>

    <?php echo '<div class="mb-4">'; ?>
        <label for="patient_profile_id" class="block text-sm font-medium text-gray-700">Patient Profile</label>
        <select name="patient_profile_id" id="patient_profile_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
            <option value="">Select Profile</option>
            <?php foreach ($profileOptions as $profileOption): ?>
                <?php $profileRow = (array) $profileOption; ?>
                <option value="<?= e((string) ($profileRow['id'] ?? '')) ?>">
                    Profile #<?= e((string) ($profileRow['id'] ?? '')) ?> (Age: <?= e((string) ($profileRow['age'] ?? '')) ?>)
                </option>
            <?php endforeach; ?>
        </select>
    <?php echo '</div>'; ?>

    <?php echo '<div class="mb-4">'; ?>
        <label for="medicine_id" class="block text-sm font-medium text-gray-700">Medicine</label>
        <select name="medicine_id" id="medicine_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
            <option value="">Select Medicine<?= e($sampleSale?->medicine?->name ? ' (e.g. ' . $sampleSale->medicine->name . ')' : '') ?></option>
            <?php foreach ($medicineOptions as $medicineOption): ?>
                <option value="<?= e($medicineOption->id) ?>" <?= e(old('medicine_id') == $medicineOption->id ? 'selected' : '') ?>><?= e($medicineOption->name) ?> (Stock: <?= e($medicineOption->stock) ?>)</option>
            <?php endforeach; ?>
        </select>
        <?php $__key = 'medicine_id'; if ($errors->has($__key)): foreach ($errors->get($__key) as $message): ?>
            <p class="text-red-500 text-xs mt-1"><?= e($message) ?></p>
        <?php endforeach; endif; ?>
    <?php echo '</div>'; ?>

    <?php echo '<div class="mb-4">'; ?>
        <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
        <input type="number" name="quantity" id="quantity" value="<?= e(old('quantity')) ?>" placeholder="<?= e($sampleSale?->quantity ?? '2') ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required min="1">
        <?php $__key = 'quantity'; if ($errors->has($__key)): foreach ($errors->get($__key) as $message): ?>
            <p class="text-red-500 text-xs mt-1"><?= e($message) ?></p>
        <?php endforeach; endif; ?>
    <?php echo '</div>'; ?>

    <?php echo '<div class="mb-4">'; ?>
        <label for="customer_name" class="block text-sm font-medium text-gray-700">Customer Name (Optional)</label>
        <input type="text" name="customer_name" id="customer_name" value="<?= e(old('customer_name')) ?>" placeholder="Enter customer name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        <?php $__key = 'customer_name'; if ($errors->has($__key)): foreach ($errors->get($__key) as $message): ?>
            <p class="text-red-500 text-xs mt-1"><?= e($message) ?></p>
        <?php endforeach; endif; ?>
    <?php echo '</div>'; ?>

    <?php echo '<div class="mb-4">'; ?>
        <label for="requested_dosage_mg" class="block text-sm font-medium text-gray-700">Requested Dosage (mg)</label>
        <input type="number" name="requested_dosage_mg" id="requested_dosage_mg" value="" min="0" step="0.01" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Optional dosage check">
    <?php echo '</div>'; ?>

    <?php echo '<div class="mb-4">'; ?>
        <label for="current_medication_ingredients" class="block text-sm font-medium text-gray-700">Current Medication Ingredients (comma-separated)</label>
        <input type="text" name="current_medication_ingredients" id="current_medication_ingredients" value="" placeholder="e.g. warfarin, ibuprofen" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
    <?php echo '</div>'; ?>

    <div id="sale-risk-loading" class="alert alert-info" style="display:none;">Checking medicine risk...</div>
    <div id="alert-box" class="mb-4"></div>

    <?php echo '<div class="form-actions">'; ?>
        <button type="submit" id="checkout-button" disabled>Record Sale</button>
        <a href="<?= e(route('records.sales')) ?>" class="btn-link-secondary">Cancel</a>
    <?php echo '</div>'; ?>
</form>
