
<?php
$action = $action ?? '';
$type = $type ?? '';
$medicine = $medicine ?? null;
$sale = $sale ?? null;
$supplier = $supplier ?? null;
$suppliers = $suppliers ?? [];
$medicines = $medicines ?? [];
ob_start();
?>
<?php echo '<div class="flex justify-between items-center w-full">'; ?>
            <?php echo '<div>'; ?>
                <?php if ($action === 'show'): ?>
                    <?php if ($type === 'medicines'): ?>
                        <h2 class="header-title">Medicine Details</h2>
                    <?php elseif ($type === 'sales'): ?>
                        <h2 class="header-title">Sale Details</h2>
                    <?php elseif ($type === 'suppliers'): ?>
                        <h2 class="header-title">Supplier Details</h2>
                    <?php endif; ?>
                <?php else: ?>
                    <?php if ($type === 'medicines'): ?>
                        <h2 class="header-title">Edit Medicine</h2>
                        <p class="header-subtitle">Update medicine information</p>
                    <?php elseif ($type === 'sales'): ?>
                        <h2 class="header-title">Edit Sale</h2>
                        <p class="header-subtitle">Update sales transaction details</p>
                    <?php elseif ($type === 'suppliers'): ?>
                        <h2 class="header-title">Edit Supplier</h2>
                        <p class="header-subtitle">Update supplier information</p>
                    <?php endif; ?>
                <?php endif; ?>
            <?php echo '</div>'; ?>
            <?php if ($action === 'show'): ?>
                <a href="<?= e(route('records.' . $type)) ?>" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Back to List</a>
            <?php endif; ?>
        <?php echo '</div>'; ?>
<?php $header = ob_get_clean(); ?>
<?php ob_start(); ?>
<?php echo '<div class="py-12">'; ?>
        <?php echo '<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">'; ?>
            <?php echo '<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">'; ?>
                <?php echo '<div class="p-6">'; ?>
                    <?php if ($action === 'show'): ?>
                        <?php /* SHOW VIEWS */ ?>
                        <?php if ($type === 'medicines' && isset($medicine)): ?>
                            <h3 class="text-lg font-medium text-gray-900 mb-4"><?= e($medicine->name) ?></h3>
                            <?php echo '<div class="grid grid-cols-1 md:grid-cols-2 gap-6">'; ?>
                                <?php echo '<div>'; ?>
                                    <strong>Description:</strong> <?= e($medicine->description ?: 'N/A') ?>
                                <?php echo '</div>'; ?>
                                <?php echo '<div>'; ?>
                                    <strong>Price:</strong> ₱<?= e(number_format($medicine->price, 2)) ?>
                                <?php echo '</div>'; ?>
                                <?php echo '<div>'; ?>
                                    <strong>Stock:</strong> <?= e($medicine->stock) ?>
                                <?php echo '</div>'; ?>
                                <?php echo '<div>'; ?>
                                    <strong>Supplier:</strong> <?= e($medicine->supplier->name) ?>
                                <?php echo '</div>'; ?>
                            <?php echo '</div>'; ?>
                            <?php echo '<div class="mt-6">'; ?>
                                <h4 class="text-md font-medium text-gray-900 mb-2">Sales History</h4>
                                <ul class="list-disc list-inside">
                                    <?php $__empty = true; foreach ($medicine->sales as $sale): $__empty = false; ?>
                                        <li><?= e($sale->quantity) ?> units sold on <?= e($sale->created_at->format('Y-m-d')) ?> for ₱<?= e(number_format($sale->total_amount, 2)) ?></li>
                                    <?php endforeach; if ($__empty): ?>
                                        <li>No sales recorded.</li>
                                    <?php endif; ?>
                                </ul>
                            <?php echo '</div>'; ?>

                        <?php elseif ($type === 'sales' && isset($sale)): ?>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Sale #<?= e($sale->id) ?></h3>
                            <?php echo '<div class="grid grid-cols-1 md:grid-cols-2 gap-6">'; ?>
                                <?php echo '<div>'; ?>
                                    <strong>Medicine:</strong> <?= e($sale->medicine->name) ?>
                                <?php echo '</div>'; ?>
                                <?php echo '<div>'; ?>
                                    <strong>Quantity:</strong> <?= e($sale->quantity) ?>
                                <?php echo '</div>'; ?>
                                <?php echo '<div>'; ?>
                                    <strong>Total Price:</strong> ₱<?= e(number_format($sale->total_amount, 2)) ?>
                                <?php echo '</div>'; ?>
                                <?php echo '<div>'; ?>
                                    <strong>Sale Date:</strong> <?= e($sale->sale_date) ?>
                                <?php echo '</div>'; ?>
                            <?php echo '</div>'; ?>

                        <?php elseif ($type === 'suppliers' && isset($supplier)): ?>
                            <h3 class="text-lg font-medium text-gray-900 mb-4"><?= e($supplier->name) ?></h3>
                            <?php echo '<div class="grid grid-cols-1 md:grid-cols-2 gap-6">'; ?>
                                <?php echo '<div>'; ?>
                                    <strong>Contact:</strong> <?= e($supplier->contact ?: 'N/A') ?>
                                <?php echo '</div>'; ?>
                                <?php echo '<div>'; ?>
                                    <strong>Address:</strong> <?= e($supplier->address ?: 'N/A') ?>
                                <?php echo '</div>'; ?>
                            <?php echo '</div>'; ?>
                            <?php echo '<div class="mt-6">'; ?>
                                <h4 class="text-md font-medium text-gray-900 mb-2">Medicines from this Supplier</h4>
                                <ul class="list-disc list-inside">
                                    <?php $__empty = true; foreach ($supplier->medicines as $medicine): $__empty = false; ?>
                                        <li><?= e($medicine->name) ?> (Stock: <?= e($medicine->stock) ?>)</li>
                                    <?php endforeach; if ($__empty): ?>
                                        <li>No medicines associated.</li>
                                    <?php endif; ?>
                                </ul>
                            <?php echo '</div>'; ?>
                        <?php endif; ?>

                    <?php else: ?>
                        <?php /* EDIT VIEWS */ ?>
                        <?php if ($type === 'medicines' && isset($medicine)): ?>
                            <form action="<?= e(route('medicines.update', $medicine)) ?>" method="POST">
                                <?= csrf_field() ?>
                                <?= method_field('PUT') ?>

                                <?php echo '<div class="mb-4">'; ?>
                                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                                    <input type="text" name="name" id="name" value="<?= e(old('name', $medicine->name)) ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                    <?php $__key = 'name'; if ($errors->has($__key)): foreach ($errors->get($__key) as $message): ?>
                                        <p class="text-red-500 text-xs mt-1"><?= e($message) ?></p>
                                    <?php endforeach; endif; ?>
                                <?php echo '</div>'; ?>

                                <?php echo '<div class="mb-4">'; ?>
                                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                    <textarea name="description" id="description" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"><?= e(old('description', $medicine->description)) ?></textarea>
                                    <?php $__key = 'description'; if ($errors->has($__key)): foreach ($errors->get($__key) as $message): ?>
                                        <p class="text-red-500 text-xs mt-1"><?= e($message) ?></p>
                                    <?php endforeach; endif; ?>
                                <?php echo '</div>'; ?>

                                <?php echo '<div class="mb-4">'; ?>
                                    <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                                    <input type="number" step="0.01" name="price" id="price" value="<?= e(old('price', $medicine->price)) ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                    <?php $__key = 'price'; if ($errors->has($__key)): foreach ($errors->get($__key) as $message): ?>
                                        <p class="text-red-500 text-xs mt-1"><?= e($message) ?></p>
                                    <?php endforeach; endif; ?>
                                <?php echo '</div>'; ?>

                                <?php echo '<div class="mb-4">'; ?>
                                    <label for="stock" class="block text-sm font-medium text-gray-700">Stock</label>
                                    <input type="number" name="stock" id="stock" value="<?= e(old('stock', $medicine->stock)) ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                    <?php $__key = 'stock'; if ($errors->has($__key)): foreach ($errors->get($__key) as $message): ?>
                                        <p class="text-red-500 text-xs mt-1"><?= e($message) ?></p>
                                    <?php endforeach; endif; ?>
                                <?php echo '</div>'; ?>

                                <?php echo '<div class="mb-4">'; ?>
                                    <label for="supplier_id" class="block text-sm font-medium text-gray-700">Supplier</label>
                                    <select name="supplier_id" id="supplier_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                        <option value="">Select Supplier</option>
                                        <?php foreach ($suppliers ?? [] as $supplierOption): ?>
                                            <?php
                                                $supplierOptionId = (string) data_get($supplierOption, 'id', '');
                                                $supplierOptionName = (string) data_get($supplierOption, 'name', '');
                                                $selectedSupplierId = (string) old('supplier_id', data_get($medicine, 'supplier_id', ''));
                                            ?>
                                            <option value="<?= e($supplierOptionId) ?>" <?= $selectedSupplierId === $supplierOptionId ? 'selected' : '' ?>><?= e($supplierOptionName) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php $__key = 'supplier_id'; if ($errors->has($__key)): foreach ($errors->get($__key) as $message): ?>
                                        <p class="text-red-500 text-xs mt-1"><?= e($message) ?></p>
                                    <?php endforeach; endif; ?>
                                <?php echo '</div>'; ?>

                                <?php echo '<div class="flex items-center justify-between">'; ?>
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        Update Medicine
                                    </button>
                                    <a href="<?= e(route('records.medicines')) ?>" class="text-gray-600 hover:text-gray-900">Cancel</a>
                                <?php echo '</div>'; ?>
                            </form>

                        <?php elseif ($type === 'sales' && isset($sale)): ?>
                            <form action="<?= e(route('sales.update', $sale)) ?>" method="POST">
                                <?= csrf_field() ?>
                                <?= method_field('PUT') ?>

                                <?php echo '<div class="mb-4">'; ?>
                                    <label for="medicine_id" class="block text-sm font-medium text-gray-700">Medicine</label>
                                    <select name="medicine_id" id="medicine_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                        <option value="">Select Medicine</option>
                                        <?php foreach ($medicines ?? [] as $medicineOption): ?>
                                            <?php
                                                $medicineOptionId = (string) data_get($medicineOption, 'id', '');
                                                $medicineOptionName = (string) data_get($medicineOption, 'name', '');
                                                $medicineOptionStock = (string) data_get($medicineOption, 'stock', '');
                                                $selectedMedicineId = (string) old('medicine_id', data_get($sale, 'medicine_id', ''));
                                            ?>
                                            <option value="<?= e($medicineOptionId) ?>" data-stock="<?= e($medicineOptionStock) ?>" <?= $selectedMedicineId === $medicineOptionId ? 'selected' : '' ?>><?= e($medicineOptionName) ?> (Stock: <?= e($medicineOptionStock) ?>)</option>
                                        <?php endforeach; ?>
                                    </select>
                                    <p class="text-xs text-gray-500 mt-1">The stock beside each medicine is the current remaining inventory.</p>
                                    <?php $__key = 'medicine_id'; if ($errors->has($__key)): foreach ($errors->get($__key) as $message): ?>
                                        <p class="text-red-500 text-xs mt-1"><?= e($message) ?></p>
                                    <?php endforeach; endif; ?>
                                <?php echo '</div>'; ?>

                                <?php echo '<div class="mb-4">'; ?>
                                    <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                                    <input type="number" name="quantity" id="quantity" value="<?= e(old('quantity', $sale->quantity)) ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required min="1">
                                    <?php $__key = 'quantity'; if ($errors->has($__key)): foreach ($errors->get($__key) as $message): ?>
                                        <p class="text-red-500 text-xs mt-1"><?= e($message) ?></p>
                                    <?php endforeach; endif; ?>
                                <?php echo '</div>'; ?>

                                <?php echo '<div class="mb-4">'; ?>
                                    <label for="sale_date" class="block text-sm font-medium text-gray-700">Sale Date</label>
                                    <input type="date" name="sale_date" id="sale_date" value="<?= e(old('sale_date', $sale->sale_date)) ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                    <?php $__key = 'sale_date'; if ($errors->has($__key)): foreach ($errors->get($__key) as $message): ?>
                                        <p class="text-red-500 text-xs mt-1"><?= e($message) ?></p>
                                    <?php endforeach; endif; ?>
                                <?php echo '</div>'; ?>

                                <?php echo '<div class="flex items-center justify-between">'; ?>
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        Update Sale
                                    </button>
                                    <a href="<?= e(route('records.sales')) ?>" class="text-gray-600 hover:text-gray-900">Cancel</a>
                                <?php echo '</div>'; ?>
                            </form>

                        <?php elseif ($type === 'suppliers' && isset($supplier)): ?>
                            <form action="<?= e(route('suppliers.update', $supplier)) ?>" method="POST">
                                <?= csrf_field() ?>
                                <?= method_field('PUT') ?>

                                <?php echo '<div class="mb-4">'; ?>
                                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                                    <input type="text" name="name" id="name" value="<?= e(old('name', $supplier->name)) ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                    <?php $__key = 'name'; if ($errors->has($__key)): foreach ($errors->get($__key) as $message): ?>
                                        <p class="text-red-500 text-xs mt-1"><?= e($message) ?></p>
                                    <?php endforeach; endif; ?>
                                <?php echo '</div>'; ?>

                                <?php echo '<div class="mb-4">'; ?>
                                    <label for="contact" class="block text-sm font-medium text-gray-700">Contact</label>
                                    <input type="text" name="contact" id="contact" value="<?= e(old('contact', $supplier->contact)) ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <?php $__key = 'contact'; if ($errors->has($__key)): foreach ($errors->get($__key) as $message): ?>
                                        <p class="text-red-500 text-xs mt-1"><?= e($message) ?></p>
                                    <?php endforeach; endif; ?>
                                <?php echo '</div>'; ?>

                                <?php echo '<div class="mb-4">'; ?>
                                    <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                                    <textarea name="address" id="address" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"><?= e(old('address', $supplier->address)) ?></textarea>
                                    <?php $__key = 'address'; if ($errors->has($__key)): foreach ($errors->get($__key) as $message): ?>
                                        <p class="text-red-500 text-xs mt-1"><?= e($message) ?></p>
                                    <?php endforeach; endif; ?>
                                <?php echo '</div>'; ?>

                                <?php echo '<div class="flex items-center justify-between">'; ?>
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        Update Supplier
                                    </button>
                                    <a href="<?= e(route('records.suppliers')) ?>" class="text-gray-600 hover:text-gray-900">Cancel</a>
                                <?php echo '</div>'; ?>
                            </form>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php echo '</div>'; ?>
            <?php echo '</div>'; ?>
        <?php echo '</div>'; ?>
    <?php echo '</div>'; ?>
<?php $slot = ob_get_clean(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ["__data", "__path"]))->render(); ?>
