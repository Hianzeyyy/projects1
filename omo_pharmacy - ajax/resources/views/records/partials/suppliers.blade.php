<?php echo '<div class="records-card-grid">'; ?>
    <?php $supplierRows = collect($suppliers ?? []); ?>
    <?php $__empty = true; foreach ($supplierRows as $supplierRow): $__empty = false; ?>
        <?php echo '<div class="record-card">'; ?>
            <?php echo '<div class="record-card-head">'; ?>
                <h3><?= e($supplierRow->name) ?></h3>
                <?php echo '<div class="action-btns">'; ?>
                    <a href="<?= e(route('suppliers.edit', $supplierRow)) ?>" class="action-btn edit" title="Edit">✏️</a>
                    <form action="<?= e(route('suppliers.destroy', $supplierRow, false)) ?>" method="POST" style="display: inline;">
                        <?= csrf_field() ?>
                        <?= method_field('DELETE') ?>
                        <button type="submit" class="action-btn delete" title="Delete" data-confirm-delete="Are you sure you want to delete this supplier?">🗑️</button>
                    </form>
                <?php echo '</div>'; ?>
            <?php echo '</div>'; ?>
            <?php echo '<div class="record-card-body">'; ?>
                <?php echo '<div class="record-meta-row">'; ?>
                    <span>📧</span>
                    <span><?= e($supplierRow->email ?: 'N/A') ?></span>
                <?php echo '</div>'; ?>
                <?php echo '<div class="record-meta-row">'; ?>
                    <span>📞</span>
                    <span><?= e($supplierRow->phone ?: 'N/A') ?></span>
                <?php echo '</div>'; ?>
                <?php echo '<div class="record-meta-row">'; ?>
                    <span>📍</span>
                    <span><?= e($supplierRow->address ?: 'N/A') ?></span>
                <?php echo '</div>'; ?>
            <?php echo '</div>'; ?>
        <?php echo '</div>'; ?>
    <?php endforeach; if ($__empty): ?>
        <?php echo '<div class="empty-state records-empty-span">'; ?>
            <?php echo '<div class="empty-state-icon">'; ?>🤝<?php echo '</div>'; ?>
            <h3>No suppliers found</h3>
            <p>Get started by adding your first supplier.</p>
            <a href="<?= e(route('suppliers.create')) ?>" class="btn btn-primary">➕ Add Supplier</a>
        <?php echo '</div>'; ?>
    <?php endif; ?>
<?php echo '</div>'; ?>
