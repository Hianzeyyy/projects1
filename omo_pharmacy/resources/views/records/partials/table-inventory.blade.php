<?php echo '<div class="data-table inventory-table">'; ?>
    <?php $inventoryRows = collect($inventory ?? []); ?>
    <table>
        <thead>
            <tr>
                <th>Medicine</th>
                <th>Batch Number</th>
                <th>Quantity</th>
                <th>Reorder Level</th>
                <th>Status</th>
                <th>Supplier</th>
                <th>Expiry Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty = true; foreach ($inventoryRows as $inventoryRow): $__empty = false; ?>
                <?php
                    if ($inventoryRow->quantity > $inventoryRow->reorder_level * 2) {
                        $statusClass = 'badge-success';
                        $statusText = 'In Stock';
                    } elseif ($inventoryRow->quantity > $inventoryRow->reorder_level) {
                        $statusClass = 'badge-warning';
                        $statusText = 'Low Stock';
                    } else {
                        $statusClass = 'badge-danger';
                        $statusText = 'Out of Stock';
                    }
                ?>
                <tr>
                    <td><strong><?= e($inventoryRow->medicine_name) ?></strong></td>
                    <td><span class="batch-number"><?= e($inventoryRow->batch_number) ?></span></td>
                    <td><strong><?= e($inventoryRow->quantity) ?></strong></td>
                    <td><?= e($inventoryRow->reorder_level) ?></td>
                    <td><span class="badge <?= e($statusClass) ?>"><?= e($statusText) ?></span></td>
                    <td><?= e($inventoryRow->supplier_name) ?></td>
                    <td><?= e(date('n/j/Y', strtotime($inventoryRow->expiry_date))) ?></td>
                    <td>
                        <?php echo '<div class="action-btns">'; ?>
                            <?php if (!empty($inventoryRow->medicine_id)): ?>
                                <a href="<?= e(route('medicines.edit', $inventoryRow->medicine_id)) ?>" class="action-btn edit" title="Edit">✏️</a>
                                <form action="<?= e(route('medicines.destroy', $inventoryRow->medicine_id)) ?>" method="POST" style="display: inline;">
                                    <?= csrf_field() ?>
                                    <?= method_field('DELETE') ?>
                                    <button type="submit" class="action-btn delete" title="Delete" data-confirm-delete="Are you sure you want to delete this medicine?">🗑️</button>
                                </form>
                            <?php endif; ?>
                        <?php echo '</div>'; ?>
                    </td>
                </tr>
            <?php endforeach; if ($__empty): ?>
                <tr>
                    <td colspan="8">
                        <?php echo '<div class="empty-state">'; ?>
                            <?php echo '<div class="empty-state-icon">'; ?>📦<?php echo '</div>'; ?>
                            <h3>No inventory items found</h3>
                            <p>Get started by adding your first inventory item.</p>
                            <a href="<?= e(route('medicines.create')) ?>" class="btn btn-primary">➕ Add Stock</a>
                        <?php echo '</div>'; ?>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
<?php echo '</div>'; ?>
