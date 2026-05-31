<?php
require_once __DIR__ . '/lib/layout.php';

return Layout::build('Dashboard', 'Dashboard', 'dashboard', function (DOMElement $main): void {

    // ── Stat cards grid ──────────────────────────────────────────────────────
    $grid = Dom::el('div', ['class' => 'grid grid-cols-2 lg:grid-cols-3 gap-5 mb-8']);
    $main->appendChild($grid);

    $cards = [
        ['id' => 's-medicines', 'label' => 'Total Medicines', 'icon' => 'fa-capsules',           'bg' => 'bg-blue-50',   'ic' => 'text-blue-600'],
        ['id' => 's-inventory', 'label' => 'Inventory Items',  'icon' => 'fa-layer-group',        'bg' => 'bg-indigo-50', 'ic' => 'text-indigo-600'],
        ['id' => 's-sales',     'label' => 'Total Sales',      'icon' => 'fa-receipt',            'bg' => 'bg-green-50',  'ic' => 'text-green-600'],
        ['id' => 's-suppliers', 'label' => 'Suppliers',         'icon' => 'fa-truck-fast',         'bg' => 'bg-violet-50', 'ic' => 'text-violet-600'],
        ['id' => 's-lowstock',  'label' => 'Low Stock Items',   'icon' => 'fa-triangle-exclamation','bg' => 'bg-amber-50',  'ic' => 'text-amber-600'],
        ['id' => 's-revenue',   'label' => 'Total Revenue',     'icon' => 'fa-dollar-sign',        'bg' => 'bg-teal-50',   'ic' => 'text-teal-600'],
    ];

    foreach ($cards as $c) {
        $card = Dom::el('div', ['class' => 'bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex items-center gap-5']);

        $ibx = Dom::el('div', ['class' => 'h-12 w-12 rounded-xl ' . $c['bg'] . ' flex items-center justify-center flex-shrink-0']);
        $ii  = Dom::el('i',   ['class' => 'fas ' . $c['icon'] . ' ' . $c['ic']]);
        $ii->appendChild(Dom::text(''));
        $ibx->appendChild($ii);
        $card->appendChild($ibx);

        $txt = Dom::el('div');
        $lbl = Dom::el('p', ['class' => 'text-xs text-gray-400 font-medium']);
        $lbl->appendChild(Dom::text($c['label']));
        $val = Dom::el('p', ['id' => $c['id'], 'class' => 'text-2xl font-extrabold text-gray-800 mt-0.5']);
        $si  = Dom::el('i', ['class' => 'fas fa-spinner spin text-base text-gray-300']);
        $si->appendChild(Dom::text(''));
        $val->appendChild($si);
        $txt->appendChild($lbl);
        $txt->appendChild($val);
        $card->appendChild($txt);
        $grid->appendChild($card);
    }

    // ── Bottom two-column grid ───────────────────────────────────────────────
    $bot = Dom::el('div', ['class' => 'grid grid-cols-1 lg:grid-cols-2 gap-6']);
    $main->appendChild($bot);

    // Helper: build a mini-table card
    $miniCard = function (string $titleText, string $subText, string $link, string $linkHref, string $tbodyId, array $cols) use ($bot): void {
        $card = Dom::el('div', ['class' => 'bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden']);
        $ch   = Dom::el('div', ['class' => 'px-6 py-4 border-b border-gray-100 flex items-center justify-between']);
        $cd   = Dom::el('div');
        $h3   = Dom::el('h3', ['class' => 'font-bold text-gray-800']);
        $h3->appendChild(Dom::text($titleText));
        $sp   = Dom::el('p', ['class' => 'text-xs text-gray-400 mt-0.5']);
        $sp->appendChild(Dom::text($subText));
        $cd->appendChild($h3);
        $cd->appendChild($sp);
        $ch->appendChild($cd);
        $a = Dom::el('a', ['href' => $linkHref, 'class' => 'text-xs text-green-600 font-bold hover:underline']);
        $a->appendChild(Dom::text($link));
        $ch->appendChild($a);
        $card->appendChild($ch);

        $tw  = Dom::el('div',   ['class' => 'overflow-x-auto']);
        $tbl = Dom::el('table', ['class' => 'w-full text-sm']);
        $th  = Dom::el('thead');
        $tr  = Dom::el('tr',    ['class' => 'bg-gray-50 text-[11px] font-bold text-gray-400 uppercase']);
        foreach ($cols as [$lbl, $cls]) {
            $th_ = Dom::el('th', ['class' => $cls]);
            $th_->appendChild(Dom::text($lbl));
            $tr->appendChild($th_);
        }
        $th->appendChild($tr);
        $tbl->appendChild($th);

        $tbody = Dom::el('tbody', ['id' => $tbodyId]);
        $lr    = Dom::el('tr');
        $ltd   = Dom::el('td', ['colspan' => (string)count($cols), 'class' => 'text-center py-6 text-gray-300']);
        $spi   = Dom::el('i', ['class' => 'fas fa-spinner spin mr-1']);
        $spi->appendChild(Dom::text(''));
        $ltd->appendChild($spi);
        $lr->appendChild($ltd);
        $tbody->appendChild($lr);
        $tbl->appendChild($tbody);
        $tw->appendChild($tbl);
        $card->appendChild($tw);
        $bot->appendChild($card);
    };

    $miniCard('Recent Sales',    'Last 5 transactions',         'View all &rarr;', '/sales',     'recent-sales-tbody', [
        ['Medicine', 'text-left px-5 py-3'],
        ['Qty',      'text-right px-5 py-3'],
        ['Total',    'text-right px-5 py-3'],
        ['Date',     'text-left px-5 py-3'],
    ]);

    $miniCard('Low Stock Alert', 'Items at or below reorder level', 'Manage &rarr;', '/inventory', 'low-stock-tbody', [
        ['Medicine', 'text-left px-5 py-3'],
        ['Qty',      'text-right px-5 py-3'],
        ['Reorder',  'text-right px-5 py-3'],
    ]);

}, <<<'JS'
async function loadDashboard() {
    try {
        const [stats, sales, inv] = await Promise.all([
            api('/api/dashboard/stats'), api('/api/sales'), api('/api/inventory')
        ]);
        document.getElementById('s-medicines').textContent = stats.total_medicines ?? 0;
        document.getElementById('s-inventory').textContent = stats.total_inventory ?? 0;
        document.getElementById('s-sales').textContent     = stats.total_sales ?? 0;
        document.getElementById('s-suppliers').textContent = stats.total_suppliers ?? 0;
        document.getElementById('s-lowstock').textContent  = stats.low_stock ?? 0;
        document.getElementById('s-revenue').textContent   = fmtMoney(stats.total_revenue ?? 0);

        const recent = sales.slice(-5).reverse();
        document.getElementById('recent-sales-tbody').innerHTML = recent.length
            ? recent.map(s => `<tr class="tbl-row border-t border-gray-50">
                <td class="px-5 py-3 font-medium text-gray-700">${escHtml(s.medicine?.name ?? 'Unknown')}</td>
                <td class="px-5 py-3 text-right text-gray-600">${s.quantity}</td>
                <td class="px-5 py-3 text-right font-bold text-green-700">${fmtMoney(s.total_price)}</td>
                <td class="px-5 py-3 text-gray-500">${fmtDate(s.sale_date)}</td></tr>`).join('')
            : '<tr><td colspan="4" class="text-center py-6 text-gray-400">No sales yet.</td></tr>';

        const low = inv.filter(i => Number(i.quantity) <= Number(i.reorder_level));
        document.getElementById('low-stock-tbody').innerHTML = low.length
            ? low.map(i => `<tr class="tbl-row border-t border-gray-50">
                <td class="px-5 py-3 font-medium text-gray-700">${escHtml(i.medicine?.name ?? 'Unknown')}</td>
                <td class="px-5 py-3 text-right font-bold text-red-600">${i.quantity}</td>
                <td class="px-5 py-3 text-right text-gray-500">${i.reorder_level}</td></tr>`).join('')
            : '<tr><td colspan="3" class="text-center py-6 text-gray-400">All stock levels healthy.</td></tr>';
    } catch (e) { showToast(e.message, 'error'); }
}
loadDashboard();
JS);
