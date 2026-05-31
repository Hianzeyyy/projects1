<?php
$bodyClass = 'records-page';
$type = $type ?? 'daily';
$date = $date ?? now()->toDateString();
$reportData = $reportData ?? [];
$sales = collect(data_get($reportData, 'sales', []));
$lowStock = collect(data_get($reportData, 'low_stock', []));
$totals = data_get($reportData, 'totals', []);
$receiptHistory = collect($receiptHistory ?? []);
$receiptDateFrom = $receiptDateFrom ?? now()->startOfMonth()->toDateString();
$receiptDateTo = $receiptDateTo ?? now()->toDateString();
$receiptSearch = $receiptSearch ?? '';

ob_start();
?>
<?php echo '<div>'; ?>
    <h2 class="header-title">Reports</h2>
    <p class="header-subtitle">View and print daily, weekly, monthly reports and transaction receipts</p>
<?php echo '</div>'; ?>
<?php echo '<div class="header-actions">'; ?>
    <a href="<?= e(route('dashboard')) ?>" class="btn btn-primary">Back to Dashboard</a>
<?php echo '</div>'; ?>
<?php
$header = ob_get_clean();

ob_start();
?>
<?php echo '<div class="records-page-shell" id="reports-shell">'; ?>
    <div id="report-ajax-success" class="auth-success" style="margin-bottom: 12px; display: none;"></div>
    <div id="report-ajax-error" class="auth-error" style="margin-bottom: 12px; display: none;"></div>

    <?php if (session('report_success')): ?>
        <div class="auth-success" style="margin-bottom: 12px;"><?= e((string) session('report_success')) ?></div>
    <?php endif; ?>

    <?php if (session('report_error')): ?>
        <div class="auth-error" style="margin-bottom: 12px;"><?= e((string) session('report_error')) ?></div>
    <?php endif; ?>

    <?php echo '<div class="report-toolbar-row">'; ?>
        <form method="GET" action="<?= e(route('reports.index', [], false)) ?>" class="mb-4 report-filter-inline" id="report-filter-form" data-ajax="report-filter">
            <?php echo '<div class="form-actions">'; ?>
                <?php echo '<div>'; ?>
                    <label for="type">Report Type</label>
                    <select id="type" name="type" class="records-search-input" style="max-width: 220px;">
                        <option value="daily" <?= $type === 'daily' ? 'selected' : '' ?>>Daily</option>
                        <option value="weekly" <?= $type === 'weekly' ? 'selected' : '' ?>>Weekly</option>
                        <option value="monthly" <?= $type === 'monthly' ? 'selected' : '' ?>>Monthly</option>
                    </select>
                <?php echo '</div>'; ?>

                <?php echo '<div>'; ?>
                    <label for="date">Reference Date</label>
                    <input id="date" type="date" name="date" value="<?= e($date) ?>" class="records-search-input" style="max-width: 220px;">
                <?php echo '</div>'; ?>

                <?php echo '<div style="align-self:flex-end;">'; ?>
                    <button type="submit" class="btn btn-primary">View Report</button>
                <?php echo '</div>'; ?>
            <?php echo '</div>'; ?>
        </form>

        <form method="POST" action="<?= e(route('reports.download', [], false)) ?>" class="mb-4 report-toolbar-action" id="report-download-form" data-ajax="report-download">
            <?= csrf_field() ?>
            <input type="hidden" name="type" value="<?= e($type) ?>">
            <input type="hidden" name="date" value="<?= e($date) ?>">
            <button type="submit" class="btn btn-primary">Export Report to PDF</button>
        </form>

        <form method="POST" action="<?= e(route('reports.archive', [], false)) ?>" class="mb-4 report-toolbar-action" id="report-archive-form" data-ajax="report-archive">
            <?= csrf_field() ?>
            <input type="hidden" name="type" value="<?= e($type) ?>">
            <input type="hidden" name="date" value="<?= e($date) ?>">
            <button type="submit" class="btn btn-primary">Save PDF to Archive</button>
        </form>
    <?php echo '</div>'; ?>

    <?php echo '<div id="report-metrics-section">'; ?>
    <?php echo '<div class="module-grid">'; ?>
        <article class="module-card">
            <h4>Transactions</h4>
            <p><?= e((string) data_get($totals, 'transactions', 0)) ?></p>
        </article>
        <article class="module-card">
            <h4>Revenue</h4>
            <p>PHP <?= e(number_format((float) data_get($totals, 'revenue', 0), 2)) ?></p>
        </article>
        <article class="module-card">
            <h4>Profit Margin</h4>
            <p><?= e(number_format((float) data_get($totals, 'profit_margin', 0), 2)) ?>%</p>
        </article>
    <?php echo '</div>'; ?>

    <h3 class="section-title" style="margin-top:1rem;">Sales Included</h3>
    <?php echo '<div class="data-table">'; ?>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>ID</th>
                    <th>Medicine</th>
                    <th>Customer</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty = true; foreach ($sales as $row): $__empty = false; ?>
                    <tr>
                        <td><?= e((string) data_get($row, 'date', '')) ?></td>
                        <td><?= e((string) data_get($row, 'id', '')) ?></td>
                        <td><?= e((string) data_get($row, 'medicine_name', '')) ?></td>
                        <td><?= e((string) data_get($row, 'customer_name', '')) ?></td>
                        <td>PHP <?= e(number_format((float) data_get($row, 'total', 0), 2)) ?></td>
                    </tr>
                <?php endforeach; if ($__empty): ?>
                    <tr>
                        <td colspan="5">No sales found for selected range.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    <?php echo '</div>'; ?>

    <h3 class="section-title" style="margin-top:1rem;">Low Stock Alert List</h3>
    <?php echo '<div class="data-table">'; ?>
        <table>
            <thead>
                <tr>
                    <th>Medicine</th>
                    <th>Quantity</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty = true; foreach ($lowStock as $row): $__empty = false; ?>
                    <tr>
                        <td><?= e((string) data_get($row, 'medicine_name', '')) ?></td>
                        <td><?= e((string) data_get($row, 'quantity', '')) ?></td>
                    </tr>
                <?php endforeach; if ($__empty): ?>
                    <tr>
                        <td colspan="2">No low-stock medicines found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    <?php echo '</div>'; ?>
    <?php echo '</div>'; ?>

    <h3 class="section-title" style="margin-top:1rem;">Receipt History</h3>
    <form method="GET" action="<?= e(route('reports.index', [], false)) ?>" class="mb-4 receipt-filter-inline" id="receipt-filter-form" data-ajax="receipt-filter">
        <input type="hidden" name="type" value="<?= e($type) ?>">
        <input type="hidden" name="date" value="<?= e($date) ?>">
        <?php echo '<div class="form-actions">'; ?>
            <?php echo '<div>'; ?>
                <label for="receipt_date_from">From</label>
                <input id="receipt_date_from" type="date" name="receipt_date_from" value="<?= e($receiptDateFrom) ?>" class="records-search-input" style="max-width: 220px;">
            <?php echo '</div>'; ?>

            <?php echo '<div>'; ?>
                <label for="receipt_date_to">To</label>
                <input id="receipt_date_to" type="date" name="receipt_date_to" value="<?= e($receiptDateTo) ?>" class="records-search-input" style="max-width: 220px;">
            <?php echo '</div>'; ?>

            <?php echo '<div>'; ?>
                <label for="receipt_search">Search</label>
                <input id="receipt_search" type="text" name="receipt_search" value="<?= e($receiptSearch) ?>" placeholder="Medicine / Customer / ID" class="records-search-input" style="max-width: 260px;">
            <?php echo '</div>'; ?>

            <?php echo '<div style="align-self:flex-end;">'; ?>
                <button type="submit" class="btn btn-primary">Filter Receipts</button>
            <?php echo '</div>'; ?>
        <?php echo '</div>'; ?>
    </form>

    <?php echo '<div class="data-table" id="receipt-history-table-section">'; ?>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Transaction ID</th>
                    <th>Medicine</th>
                    <th>Customer</th>
                    <th>Total</th>
                    <th>Receipt</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty = true; foreach ($receiptHistory as $receipt): $__empty = false; ?>
                    <tr>
                        <td><?= e(optional($receipt->created_at)->format('Y-m-d H:i')) ?></td>
                        <td><?= e((string) $receipt->id) ?></td>
                        <td><?= e((string) ($receipt->medicine_name ?? '-')) ?></td>
                        <td><?= e((string) ($receipt->customer_name ?? 'Walk-in')) ?></td>
                        <td>PHP <?= e(number_format((float) ($receipt->total_amount ?? 0), 2)) ?></td>
                        <td>
                            <a href="<?= e(route('sales.receipt', $receipt)) ?>" class="btn btn-primary">Print Receipt</a>
                        </td>
                    </tr>
                <?php endforeach; if ($__empty): ?>
                    <tr>
                        <td colspan="6">No receipts found for the selected filters.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    <?php echo '</div>'; ?>

<?php echo '</div>'; ?>
<?php
$slot = ob_get_clean();
echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render();
