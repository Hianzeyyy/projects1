<?php echo '<div class="data-table">'; ?>
    <?php $salesRows = collect($sales ?? []); ?>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Medicine</th>
                <th>Customer</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Total Amount</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty = true; foreach ($salesRows as $salesRow): $__empty = false; ?>
                <tr>
                    <td class="sales-date-cell">
                        <?php echo '<div class="sales-date-text">'; ?><?= e(date('n/j/Y', strtotime($salesRow->created_at))) ?><?php echo '</div>'; ?>
                        <?php echo '<div class="sales-time-text">'; ?><?= e(date('g:i A', strtotime($salesRow->created_at))) ?><?php echo '</div>'; ?>
                    </td>
                    <td class="sales-medicine-cell"><strong><?= e($salesRow->medicine_name) ?></strong></td>
                    <td class="sales-customer-cell"><?= e($salesRow->customer_name ?? 'Walk-in') ?></td>
                    <td class="sales-quantity-cell"><?= e($salesRow->quantity) ?></td>
                    <td class="sales-price-cell">₱<?= e(number_format($salesRow->unit_price, 2)) ?></td>
                    <td class="sales-total-cell"><strong class="sales-total-amount">₱<?= e(number_format($salesRow->total_amount, 2)) ?></strong></td>
                    <td>
                        <?php echo '<div class="action-btns">'; ?>
                            <a href="<?= e(route('sales.receipt', $salesRow)) ?>" class="action-btn view" title="Print Receipt">🧾</a>
                            <a href="<?= e(route('sales.edit', $salesRow)) ?>" class="action-btn edit" title="Edit">✏️</a>
                            <form action="<?= e(route('sales.destroy', $salesRow, false)) ?>" method="POST" style="display: inline;">
                                <?= csrf_field() ?>
                                <?= method_field('DELETE') ?>
                                <button type="submit" class="action-btn delete" title="Delete" data-confirm-delete="Are you sure you want to delete this sale?">🗑️</button>
                            </form>
                        <?php echo '</div>'; ?>
                    </td>
                </tr>
            <?php endforeach; if ($__empty): ?>
                <tr>
                    <td colspan="7">
                        <?php echo '<div class="empty-state">'; ?>
                            <?php echo '<div class="empty-state-icon">'; ?>💰<?php echo '</div>'; ?>
                            <h3>No sales found</h3>
                            <p>Get started by recording your first sale.</p>
                            <a href="<?= e(route('sales.create')) ?>" class="btn btn-primary">➕ Record Sale</a>
                        <?php echo '</div>'; ?>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
<?php echo '</div>'; ?>
