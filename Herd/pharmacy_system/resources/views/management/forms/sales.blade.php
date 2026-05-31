<form action="<?= e(route('sales.store')) ?>" method="POST">
    <?= csrf_field() ?>

    <?php $medicineOptions = collect($medicines ?? []); ?>

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

    <?php echo '<div class="form-actions">'; ?>
        <button type="submit">Record Sale</button>
        <a href="<?= e(route('records.sales')) ?>" class="btn-link-secondary">Cancel</a>
    <?php echo '</div>'; ?>
</form>
