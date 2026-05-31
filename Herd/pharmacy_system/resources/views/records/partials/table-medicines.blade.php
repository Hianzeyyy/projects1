
<?php echo '<div class="data-table data-table-responsive">'; ?>
    <?php $medicineRows = collect($medicines ?? []); ?>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Category</th>
                <th>Manufacturer</th>
                <th>Price</th>
                <th>Expiry Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty = true; foreach ($medicineRows as $medicineRow): $__empty = false; ?>
                <tr>
                    <td><strong><?= e($medicineRow->name) ?></strong></td>
                    <td><?= e($medicineRow->category ?: 'General') ?></td>
                    <td><?= e($medicineRow->manufacturer) ?></td>
                    <td><strong>₱<?= e(number_format($medicineRow->price, 2)) ?></strong></td>
                    <td><?= e(date('m/d/Y', strtotime($medicineRow->expiry_date))) ?></td>
                    <td>
                        <?php echo '<div class="action-btns">'; ?>
                            <a href="<?= e(route('medicines.edit', $medicineRow)) ?>" class="action-btn edit" title="Edit">✏️</a>
                            <form action="<?= e(route('medicines.destroy', $medicineRow)) ?>" method="POST" style="display: inline;">
                                <?= csrf_field() ?>
                                <?= method_field('DELETE') ?>
                                <button type="submit" class="action-btn delete" title="Delete" data-confirm-delete="Are you sure you want to delete this medicine?">🗑️</button>
                            </form>
                        <?php echo '</div>'; ?>
                    </td>
                </tr>
            <?php endforeach; if ($__empty): ?>
                <tr>
                    <td colspan="6">
                        <?php echo '<div class="empty-state">'; ?>
                            <?php echo '<div class="empty-state-icon">'; ?>💊<?php echo '</div>'; ?>
                            <h3>No medicines found</h3>
                            <p>Get started by adding your first medicine to the inventory.</p>
                            <a href="<?= e(route('medicines.create')) ?>" class="btn btn-primary">➕ Add Medicine</a>
                        <?php echo '</div>'; ?>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
<?php echo '</div>'; ?>
