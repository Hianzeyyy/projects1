<form action="<?= e(route('medicines.store')) ?>" method="POST">
    <?= csrf_field() ?>

    <?php echo '<div class="mb-4">'; ?>
        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
        <input type="text" name="name" id="name" value="<?= e(old('name')) ?>" placeholder="Paracetamol 500mg" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
        <?php $__key = 'name'; if ($errors->has($__key)): foreach ($errors->get($__key) as $message): ?>
            <p class="text-red-500 text-xs mt-1"><?= e($message) ?></p>
        <?php endforeach; endif; ?>
    <?php echo '</div>'; ?>

    <?php echo '<div class="mb-4">'; ?>
        <label for="stock" class="block text-sm font-medium text-gray-700">Stock</label>
        <input type="number" name="stock" id="stock" value="<?= e(old('stock', '0')) ?>" min="0" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
        <?php $__key = 'stock'; if ($errors->has($__key)): foreach ($errors->get($__key) as $message): ?>
            <p class="text-red-500 text-xs mt-1"><?= e($message) ?></p>
        <?php endforeach; endif; ?>
    <?php echo '</div>'; ?>

    <?php echo '<div class="mb-4">'; ?>
        <label for="supplier_id" class="block text-sm font-medium text-gray-700">Supplier</label>
        <select name="supplier_id" id="supplier_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
            <option value="">Select supplier</option>
            <?php foreach (($suppliers ?? []) as $supplierOption): ?>
                <option value="<?= e($supplierOption->id) ?>" <?= old('supplier_id') == $supplierOption->id ? 'selected' : '' ?>><?= e($supplierOption->name) ?></option>
            <?php endforeach; ?>
        </select>
        <?php $__key = 'supplier_id'; if ($errors->has($__key)): foreach ($errors->get($__key) as $message): ?>
            <p class="text-red-500 text-xs mt-1"><?= e($message) ?></p>
        <?php endforeach; endif; ?>
    <?php echo '</div>'; ?>

    <?php echo '<div class="mb-4">'; ?>
        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
        <textarea name="description" id="description" placeholder="Pain relief and fever reducer" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"><?= e(old('description')) ?></textarea>
        <?php $__key = 'description'; if ($errors->has($__key)): foreach ($errors->get($__key) as $message): ?>
            <p class="text-red-500 text-xs mt-1"><?= e($message) ?></p>
        <?php endforeach; endif; ?>
    <?php echo '</div>'; ?>

    <?php echo '<div class="mb-4">'; ?>
        <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
        <input type="number" step="0.01" name="price" id="price" value="<?= e(old('price')) ?>" placeholder="5.99" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
        <?php $__key = 'price'; if ($errors->has($__key)): foreach ($errors->get($__key) as $message): ?>
            <p class="text-red-500 text-xs mt-1"><?= e($message) ?></p>
        <?php endforeach; endif; ?>
    <?php echo '</div>'; ?>

    <?php echo '<div class="form-actions">'; ?>
        <button type="submit">Create Medicine</button>
        <a href="<?= e(route('records.medicines')) ?>" class="btn-link-secondary">Cancel</a>
    <?php echo '</div>'; ?>
</form>
