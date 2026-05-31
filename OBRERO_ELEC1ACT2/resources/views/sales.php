<?php
require_once __DIR__ . '/lib/layout.php';

return Layout::build('Sales', 'Sales', 'sales', function (DOMElement $main): void {

    // ── Summary bar ──────────────────────────────────────────────────────────
    $grid = Dom::el('div', ['class' => 'grid grid-cols-3 gap-5 mb-6']);
    $main->appendChild($grid);

    foreach ([
        ['summary-count',   'Total Transactions', 'fa-receipt',     'bg-blue-50',   'text-blue-600'],
        ['summary-units',   'Units Sold',          'fa-box-open',    'bg-indigo-50', 'text-indigo-600'],
        ['summary-revenue', 'Total Revenue',       'fa-dollar-sign', 'bg-green-50',  'text-green-600'],
    ] as [$id, $lbl, $icon, $bg, $ic]) {
        $c  = Dom::el('div', ['class' => 'bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4']);
        $iw = Dom::el('div', ['class' => 'h-10 w-10 rounded-xl ' . $bg . ' flex items-center justify-center']);
        $ii = Dom::el('i',   ['class' => 'fas ' . $icon . ' ' . $ic . ' text-sm']);
        $ii->appendChild(Dom::text(''));
        $iw->appendChild($ii);
        $c->appendChild($iw);
        $tx = Dom::el('div');
        $pl = Dom::el('p', ['class' => 'text-xs text-gray-400']);
        $pl->appendChild(Dom::text($lbl));
        $pv = Dom::el('p', ['id' => $id, 'class' => 'text-xl font-extrabold text-gray-800']);
        $pv->appendChild(Dom::text("\xE2\x80\x94"));
        $tx->appendChild($pl); $tx->appendChild($pv);
        $c->appendChild($tx); $grid->appendChild($c);
    }

    // ── Table card ───────────────────────────────────────────────────────────
    $card = Dom::el('div', ['class' => 'bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden']);

    $hdr    = Dom::el('div', ['class' => 'px-6 py-5 border-b border-gray-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4']);
    $td     = Dom::el('div');
    $h2     = Dom::el('h2', ['class' => 'font-bold text-gray-800']);
    $h2->appendChild(Dom::text('Sales Ledger'));
    $sb     = Dom::el('p', ['class' => 'text-xs text-gray-400 mt-0.5']);
    $sb->appendChild(Dom::text('All recorded transactions'));
    $td->appendChild($h2); $td->appendChild($sb); $hdr->appendChild($td);
    $addBtn = Dom::el('button', ['onclick' => 'openModal()', 'class' => 'flex items-center gap-2 px-4 py-2.5 bg-green-600 text-white text-sm font-bold rounded-xl hover:bg-green-700 transition shadow-sm whitespace-nowrap']);
    $pi     = Dom::el('i', ['class' => 'fas fa-plus text-xs']); $pi->appendChild(Dom::text('')); $addBtn->appendChild($pi); $addBtn->appendChild(Dom::text(' Record Sale'));
    $hdr->appendChild($addBtn); $card->appendChild($hdr);

    $tw  = Dom::el('div', ['class' => 'overflow-x-auto']);
    $tbl = Dom::el('table', ['class' => 'w-full text-sm']);
    $th  = Dom::el('thead');
    $tr  = Dom::el('tr', ['class' => 'bg-gray-50 border-b border-gray-100 text-[11px] font-bold text-gray-400 uppercase tracking-wider']);
    foreach ([['#','text-left px-6 py-3.5'],['Medicine','text-left px-6 py-3.5'],['Customer','text-left px-6 py-3.5'],['Qty','text-right px-6 py-3.5'],['Unit Price','text-right px-6 py-3.5'],['Total','text-right px-6 py-3.5'],['Date','text-left px-6 py-3.5']] as [$l,$c]) {
        $te = Dom::el('th', ['class' => $c]); $te->appendChild(Dom::text($l)); $tr->appendChild($te); }
    $th->appendChild($tr); $tbl->appendChild($th);
    $tbody = Dom::el('tbody', ['id' => 'sales-tbody']);
    $lr = Dom::el('tr'); $ltd = Dom::el('td', ['colspan' => '7', 'class' => 'text-center py-12 text-gray-300']);
    $spi = Dom::el('i', ['class' => 'fas fa-spinner spin mr-2']); $spi->appendChild(Dom::text(''));
    $ltd->appendChild($spi); $ltd->appendChild(Dom::text('Loading sales...')); $lr->appendChild($ltd); $tbody->appendChild($lr);
    $tbl->appendChild($tbody); $tw->appendChild($tbl); $card->appendChild($tw);
    $ft = Dom::el('div', ['class' => 'px-6 py-3 border-t border-gray-100 bg-gray-50']);
    $rc = Dom::el('p', ['id' => 'rec-count', 'class' => 'text-xs text-gray-400']); $rc->appendChild(Dom::text("\xE2\x80\x94"));
    $ft->appendChild($rc); $card->appendChild($ft); $main->appendChild($card);

    // ── Modal ────────────────────────────────────────────────────────────────
    $modal = Dom::el('div', ['id' => 'modal', 'class' => 'hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40']);
    $mbox  = Dom::el('div', ['class' => 'bg-white rounded-2xl shadow-2xl w-full max-w-lg']);
    $mh = Dom::el('div', ['class' => 'px-6 py-5 border-b border-gray-100 flex items-center justify-between']);
    $mt = Dom::el('h3', ['class' => 'text-lg font-bold text-gray-800']); $mt->appendChild(Dom::text('Record New Sale'));
    $xb = Dom::el('button', ['onclick' => 'closeModal()', 'class' => 'h-8 w-8 flex items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100 transition']);
    $xi = Dom::el('i', ['class' => 'fas fa-times text-sm']); $xi->appendChild(Dom::text('')); $xb->appendChild($xi);
    $mh->appendChild($mt); $mh->appendChild($xb); $mbox->appendChild($mh);
    $frm = Dom::el('form', ['id' => 'modal-form', 'class' => 'px-6 py-5 space-y-4', 'onsubmit' => 'handleSubmit(event)']);

    // medicine select
    $mw = Dom::el('div');
    $ml = Dom::el('label', ['class' => 'block text-xs font-bold text-gray-600 mb-1.5', 'for' => 'f-medicine']); $ml->appendChild(Dom::text('Medicine'));
    $ms = Dom::el('span', ['class' => 'text-red-500']); $ms->appendChild(Dom::text(' *')); $ml->appendChild($ms);
    $mw->appendChild($ml);
    $msel = Dom::el('select', ['id' => 'f-medicine', 'required' => 'required', 'onchange' => 'onMedChange(this)', 'class' => 'ring-inp w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm']);
    $dopt = Dom::el('option', ['value' => '']); $dopt->appendChild(Dom::text('Select medicine...')); $msel->appendChild($dopt);
    $mw->appendChild($msel);
    $si2 = Dom::el('div', ['id' => 'stock-info', 'class' => 'mt-1.5 text-xs text-gray-400 hidden']); $si2->appendChild(Dom::text(''));
    $mw->appendChild($si2); $frm->appendChild($mw);

    $inp = function (string $id, string $lbl, string $type, array $ex = [], bool $req = false) {
        $w  = Dom::el('div'); $lb = Dom::el('label', ['class' => 'block text-xs font-bold text-gray-600 mb-1.5', 'for' => $id]); $lb->appendChild(Dom::text($lbl));
        if ($req) { $st = Dom::el('span', ['class' => 'text-red-500']); $st->appendChild(Dom::text(' *')); $lb->appendChild($st); }
        $w->appendChild($lb);
        $a = array_merge(['id'=>$id,'type'=>$type,'class'=>'ring-inp w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm'], $req ? ['required'=>'required'] : [], $ex);
        $w->appendChild(Dom::el('input', $a)); return $w;
    };
    $frm->appendChild($inp('f-customer', 'Customer Name', 'text', ['placeholder' => 'Walk-in Customer']));

    $g = Dom::el('div', ['class' => 'grid grid-cols-3 gap-3']);
    $g->appendChild($inp('f-qty',   'Quantity', 'number', ['min'=>'1','placeholder'=>'1','oninput'=>'calcTotal()'], true));
    $g->appendChild($inp('f-price', 'Unit Price','number', ['step'=>'0.01','readonly'=>'readonly','class'=>'ring-inp w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm bg-gray-50']));
    $g->appendChild($inp('f-total', 'Total',    'text',   ['readonly'=>'readonly','class'=>'ring-inp w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm bg-gray-50 font-bold text-green-700']));
    $frm->appendChild($g);

    $br = Dom::el('div', ['class' => 'flex gap-3 pt-1']);
    $cb = Dom::el('button', ['type' => 'button', 'onclick' => 'closeModal()', 'class' => 'flex-1 py-2.5 border border-gray-200 text-gray-600 text-sm font-bold rounded-xl hover:bg-gray-50 transition']); $cb->appendChild(Dom::text('Cancel'));
    $sb = Dom::el('button', ['type' => 'submit', 'id' => 'submit-btn', 'class' => 'flex-1 py-2.5 bg-green-600 text-white text-sm font-bold rounded-xl hover:bg-green-700 transition shadow-sm']); $sb->appendChild(Dom::text('Record Sale'));
    $br->appendChild($cb); $br->appendChild($sb); $frm->appendChild($br);
    $mbox->appendChild($frm); $modal->appendChild($mbox); $main->appendChild($modal);

}, <<<'JS'
let sales = [], allMedicines = [], allInventory = [];
async function load() {
    try {
        [sales, allMedicines, allInventory] = await Promise.all([api("/api/sales"), api("/api/medicines"), api("/api/inventory")]);
        document.getElementById("f-medicine").innerHTML = '<option value="">Select medicine...</option>' +
            allMedicines.map(m => `<option value="${m.id}" data-price="${m.price}">${escHtml(m.name)}</option>`).join("");
        render(sales);
    } catch(e) { showToast(e.message, "error"); }
}
function onMedChange(sel) {
    const opt = sel.selectedOptions[0];
    document.getElementById("f-price").value = opt?.dataset?.price ?? "";
    calcTotal();
    const inv  = allInventory.find(i => i.medicine_id == sel.value);
    const info = document.getElementById("stock-info");
    if (inv) {
        info.textContent = `Available stock: ${inv.quantity} unit${inv.quantity!==1?"s":""}`;
        info.className = "mt-1.5 text-xs block " + (Number(inv.quantity)===0 ? "text-red-500 font-bold" : Number(inv.quantity)<=Number(inv.reorder_level) ? "text-amber-600 font-bold" : "text-gray-400");
    } else { info.textContent = "No inventory record found."; info.className = "mt-1.5 text-xs text-gray-400 block"; }
}
function calcTotal() {
    const qty = parseFloat(document.getElementById("f-qty").value) || 0;
    const prc = parseFloat(document.getElementById("f-price").value) || 0;
    document.getElementById("f-total").value = fmtMoney(qty * prc);
}
function render(data) {
    const count = data.length;
    document.getElementById("rec-count").textContent      = `Showing ${count} transaction${count!==1?"s":""}`;
    document.getElementById("summary-count").textContent  = count;
    document.getElementById("summary-units").textContent  = data.reduce((a, s) => a + Number(s.quantity), 0);
    document.getElementById("summary-revenue").textContent = fmtMoney(data.reduce((a, s) => a + Number(s.total_price), 0));
    const tbody = document.getElementById("sales-tbody");
    if (!data.length) { tbody.innerHTML = '<tr><td colspan="7" class="text-center py-12 text-gray-400">No sales yet.</td></tr>'; return; }
    tbody.innerHTML = [...data].reverse().map((s, i) => `<tr class="tbl-row border-t border-gray-50">
        <td class="px-6 py-4 text-xs text-gray-400">${i+1}</td>
        <td class="px-6 py-4 font-semibold text-gray-800">${escHtml(s.medicine?.name ?? "Unknown")}</td>
        <td class="px-6 py-4 text-gray-600">${escHtml(s.customer_name || "Walk-in")}</td>
        <td class="px-6 py-4 text-right font-medium text-gray-800">${s.quantity}</td>
        <td class="px-6 py-4 text-right text-gray-600">${fmtMoney(s.unit_price ?? 0)}</td>
        <td class="px-6 py-4 text-right font-bold text-green-700">${fmtMoney(s.total_price)}</td>
        <td class="px-6 py-4 text-gray-500">${fmtDate(s.sale_date)}</td></tr>`).join("");
}
function openModal() {
    document.getElementById("f-medicine").value = "";
    document.getElementById("f-customer").value = "";
    document.getElementById("f-qty").value       = "";
    document.getElementById("f-price").value     = "";
    document.getElementById("f-total").value     = "";
    document.getElementById("stock-info").className = "mt-1.5 text-xs text-gray-400 hidden";
    document.getElementById("modal").classList.remove("hidden");
    setTimeout(() => document.getElementById("f-medicine").focus(), 60);
}
function closeModal() { document.getElementById("modal").classList.add("hidden"); }
document.getElementById("modal").addEventListener("click", e => { if (e.target === document.getElementById("modal")) closeModal(); });
async function handleSubmit(e) {
    e.preventDefault();
    const btn = document.getElementById("submit-btn");
    btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner spin mr-1"></i> Saving...';
    const body = {
        medicine_id:   parseInt(document.getElementById("f-medicine").value),
        customer_name: document.getElementById("f-customer").value.trim() || "Walk-in",
        quantity:      parseInt(document.getElementById("f-qty").value),
        unit_price:    parseFloat(document.getElementById("f-price").value) || 0,
    };
    try { await api("/api/sales", "POST", body); showToast("Sale recorded!"); closeModal(); load(); }
    catch(e) { showToast(e.message, "error"); }
    finally { btn.disabled = false; btn.textContent = "Record Sale"; }
}
load();
JS);
