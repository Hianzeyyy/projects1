<form action="<?= e(route('suppliers.store', [], false)) ?>" method="POST">
    <?= csrf_field() ?>

    <?php echo '<div class="mb-4">'; ?>
        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
        <input type="text" name="name" id="name" value="<?= e(old('name')) ?>" placeholder="<?= e($sampleSupplier?->name ?? 'MediCore Distributors') ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
        <?php $__key = 'name'; if ($errors->has($__key)): foreach ($errors->get($__key) as $message): ?>
            <p class="text-red-500 text-xs mt-1"><?= e($message) ?></p>
        <?php endforeach; endif; ?>
    <?php echo '</div>'; ?>

    <?php echo '<div class="mb-4">'; ?>
        <label for="contact" class="block text-sm font-medium text-gray-700">Contact</label>
        <input type="text" name="contact" id="contact" value="<?= e(old('contact')) ?>" placeholder="<?= e($sampleSupplier?->contact ?? '+63 912 345 6789') ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        <?php $__key = 'contact'; if ($errors->has($__key)): foreach ($errors->get($__key) as $message): ?>
            <p class="text-red-500 text-xs mt-1"><?= e($message) ?></p>
        <?php endforeach; endif; ?>
    <?php echo '</div>'; ?>

    <?php echo '<div class="mb-4">'; ?>
        <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
        <textarea name="address" id="address" placeholder="<?= e($sampleSupplier?->address ?? '123 Health Avenue, Manila') ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"><?= e(old('address')) ?></textarea>
        <?php $__key = 'address'; if ($errors->has($__key)): foreach ($errors->get($__key) as $message): ?>
            <p class="text-red-500 text-xs mt-1"><?= e($message) ?></p>
        <?php endforeach; endif; ?>
    <?php echo '</div>'; ?>

    <?php echo '<div class="form-actions">'; ?>
        <button type="submit">Create Supplier</button>
        <a href="<?= e(route('records.suppliers')) ?>" class="btn-link-secondary">Cancel</a>
    <?php echo '</div>'; ?>
</form>
