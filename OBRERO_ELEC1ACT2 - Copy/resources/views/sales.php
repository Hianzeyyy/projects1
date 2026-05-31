<?php
require_once __DIR__ . '/lib/layout.php';

return Layout::build('Sales', 'Sales', 'sales', function (DOMElement $main): void {

    // ── Summary bar ──────────────────────────────────────────────────────────
    $grid = Dom::el('div', ['class' => 'grid grid-cols-3 gap-5 mb-6']);
    $main->appendChild($grid);

    foreach ([
        ['summary-count',   'Total Transactions', 'fa-receipt',     'bg-blue-50',   'text-blue-600'],
        ['summary-units',   'Units Sold',          'fa-box-open',    'bg-indigo-50', 'text-indigo-600'],
        ['summary-revenue', 'Total Revenue',       'fa-peso-sign',   'bg-[#b651f0] bg-opacity-10',  'text-[#b651f0]'],
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
    $btnWrap = Dom::el('div', ['class' => 'flex items-center gap-2 w-full sm:w-auto']);
    $srchWrap = Dom::el('div', ['class' => 'relative flex-1 sm:w-64']);
    $srchI = Dom::el('i', ['class' => 'fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none']);
    $srchI->appendChild(Dom::text(''));
    $srchWrap->appendChild($srchI);
    $srchWrap->appendChild(Dom::el('input', [
        'id' => 'search',
        'type' => 'text',
        'placeholder' => 'Search medicine or customer...',
        'class' => 'ring-inp w-full pl-9 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-gray-50 focus:bg-white',
    ]));
    $btnWrap->appendChild($srchWrap);
    $parserBtn = Dom::el('a', ['href' => '/ai/prescription-parser', 'class' => 'flex items-center gap-2 px-4 py-2.5 border border-violet-200 text-violet-700 text-sm font-bold rounded-xl hover:bg-violet-50 transition whitespace-nowrap']);
    $parserI = Dom::el('i', ['class' => 'fas fa-robot text-xs']);
    $parserI->appendChild(Dom::text(''));
    $parserBtn->appendChild($parserI);
    $parserBtn->appendChild(Dom::text(' NLP Parser'));

    $addBtn = Dom::el('button', ['onclick' => 'openModal()', 'class' => 'flex items-center gap-2 px-4 py-2.5 bg-[#b651f0] text-white text-sm font-bold rounded-xl hover:bg-[#a040c0] transition shadow-sm whitespace-nowrap']);
    $pi     = Dom::el('i', ['class' => 'fas fa-plus text-xs']); $pi->appendChild(Dom::text('')); $addBtn->appendChild($pi); $addBtn->appendChild(Dom::text(' Record Sale'));
    $btnWrap->appendChild($parserBtn);
    $btnWrap->appendChild($addBtn);
    $hdr->appendChild($btnWrap); $card->appendChild($hdr);

    $tw  = Dom::el('div', ['class' => 'overflow-x-auto']);
    $tbl = Dom::el('table', ['class' => 'w-full text-sm']);
    $th  = Dom::el('thead');
    $tr  = Dom::el('tr', ['class' => 'bg-gray-50 border-b border-gray-100 text-[11px] font-bold text-gray-400 uppercase tracking-wider']);
    foreach ([['#','text-left px-6 py-3.5'],['Medicine','text-left px-6 py-3.5'],['Customer','text-left px-6 py-3.5'],['Qty','text-right px-6 py-3.5'],['Unit Price','text-right px-6 py-3.5'],['Total','text-right px-6 py-3.5'],['Date','text-left px-6 py-3.5'],['Actions','text-center px-6 py-3.5']] as [$l,$c]) {
        $te = Dom::el('th', ['class' => $c]); $te->appendChild(Dom::text($l)); $tr->appendChild($te); }
    $th->appendChild($tr); $tbl->appendChild($th);
    $tbody = Dom::el('tbody', ['id' => 'sales-tbody']);
    // Sample/demo rows for UI consistency
    $sampleRows = [
        [1, 'Paracetamol 500mg', 'Walk-in', 2, '₱24.00', '₱48.00', '2027-08-01'],
        [2, 'Amoxicillin 250mg', 'Juan Dela Cruz', 3, '₱29.33', '₱88.00', '2027-09-15'],
    ];
    foreach ($sampleRows as $row) {
        $tr = Dom::el('tr', ['class' => 'tbl-row border-t border-gray-50']);
        $tr->appendChild(Dom::el('td', ['class' => 'px-6 py-4 text-xs text-gray-400']))->appendChild(Dom::text($row[0]));
        $tr->appendChild(Dom::el('td', ['class' => 'px-6 py-4 font-semibold text-gray-800']))->appendChild(Dom::text($row[1]));
        $tr->appendChild(Dom::el('td', ['class' => 'px-6 py-4 text-gray-600']))->appendChild(Dom::text($row[2]));
        $tr->appendChild(Dom::el('td', ['class' => 'px-6 py-4 text-right font-medium text-gray-800']))->appendChild(Dom::text($row[3]));
        $tr->appendChild(Dom::el('td', ['class' => 'px-6 py-4 text-right text-gray-600']))->appendChild(Dom::text($row[4]));
        $tr->appendChild(Dom::el('td', ['class' => 'px-6 py-4 text-right font-bold text-[#b651f0]']))->appendChild(Dom::text($row[5]));
        $tr->appendChild(Dom::el('td', ['class' => 'px-6 py-4 text-gray-500']))->appendChild(Dom::text($row[6]));
        // Actions cell with Edit/Delete buttons (disabled for demo)
        $actionsTd = Dom::el('td', ['class' => 'px-6 py-4 text-center']);
        $actionsDiv = Dom::el('div', ['class' => 'inline-flex items-center gap-1.5']);
        $editBtn = Dom::el('button', [
            'class' => 'h-8 w-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 opacity-50 cursor-not-allowed',
            'title' => 'Edit (demo row)',
            'disabled' => 'disabled',
        ]);
        $editIcon = Dom::el('i', ['class' => 'fas fa-pencil text-xs']);
        $editBtn->appendChild($editIcon);
        $delBtn = Dom::el('button', [
            'class' => 'h-8 w-8 flex items-center justify-center rounded-lg bg-red-50 text-red-500 opacity-50 cursor-not-allowed',
            'title' => 'Delete (demo row)',
            'disabled' => 'disabled',
        ]);
        $delIcon = Dom::el('i', ['class' => 'fas fa-trash text-xs']);
        $delBtn->appendChild($delIcon);
        $actionsDiv->appendChild($editBtn);
        $actionsDiv->appendChild($delBtn);
        $actionsTd->appendChild($actionsDiv);
        $tr->appendChild($actionsTd);
        $tbody->appendChild($tr);
    }
    $tbl->appendChild($tbody); $tw->appendChild($tbl); $card->appendChild($tw);
    $ft = Dom::el('div', ['class' => 'px-6 py-3 border-t border-gray-100 bg-gray-50']);
    $rc = Dom::el('p', ['id' => 'rec-count', 'class' => 'text-xs text-gray-400']); $rc->appendChild(Dom::text("\xE2\x80\x94"));
    $ft->appendChild($rc); $card->appendChild($ft); $main->appendChild($card);

    // ── Modal ────────────────────────────────────────────────────────────────
    $modal = Dom::el('div', ['id' => 'modal', 'class' => 'hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40']);
    $mbox  = Dom::el('div', ['class' => 'bg-white rounded-2xl shadow-2xl w-full max-w-lg']);
    $mh = Dom::el('div', ['class' => 'px-6 py-5 border-b border-gray-100 flex items-center justify-between']);
    $mt = Dom::el('h3', ['id' => 'modal-title', 'class' => 'text-lg font-bold text-gray-800']); $mt->appendChild(Dom::text('Record New Sale'));
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
    $g->appendChild($inp('f-total', 'Total',    'text',   ['readonly'=>'readonly','class'=>'ring-inp w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm bg-gray-50 font-bold text-[#b651f0]']));
    $frm->appendChild($g);

    $br = Dom::el('div', ['class' => 'flex gap-3 pt-1']);
    $cb = Dom::el('button', ['type' => 'button', 'onclick' => 'closeModal()', 'class' => 'flex-1 py-2.5 border border-gray-200 text-gray-600 text-sm font-bold rounded-xl hover:bg-gray-50 transition']); $cb->appendChild(Dom::text('Cancel'));
    $sb = Dom::el('button', ['type' => 'submit', 'id' => 'submit-btn', 'class' => 'flex-1 py-2.5 bg-[#b651f0] text-white text-sm font-bold rounded-xl hover:bg-[#a040c0] transition shadow-sm']); $sb->appendChild(Dom::text('Record Sale'));
    $br->appendChild($cb); $br->appendChild($sb); $frm->appendChild($br);
    $mbox->appendChild($frm); $modal->appendChild($mbox); $main->appendChild($modal);

}, <<<'JS'
let sales = [];
let filtered = [];
let allMedicines = [];
let allInventory = [];
let editSaleId = null;

function toArray(payload) {
    if (Array.isArray(payload)) return payload;
    if (payload && Array.isArray(payload.data)) return payload.data;
    return [];
}

function uniqueMedicinesFromInventory(inventoryRows) {
    const seen = new Set();
    const rows = [];
    for (const inv of inventoryRows) {
        const medicineId = Number(inv.medicine_id);
        const medName = inv.medicine?.name ?? inv.medicine_name ?? "";
        if (!medicineId || !medName || seen.has(medicineId)) continue;
        seen.add(medicineId);
        rows.push({
            id: medicineId,
            name: medName,
            price: Number(inv.medicine?.price ?? 0),
        });
    }
    return rows;
}

function populateMedicineSelect() {
    const select = document.getElementById("f-medicine");
    const medicinesForSelect = allMedicines.length
        ? allMedicines
        : uniqueMedicinesFromInventory(allInventory);

    select.innerHTML = '<option value="">Select medicine...</option>' +
        medicinesForSelect.map(m => `<option value="${m.id}" data-price="${Number(m.price ?? 0)}">${escHtml(m.name ?? "Medicine")}</option>`).join("");
}

async function refreshCatalogForModal() {
    const [medRes, invRes] = await Promise.allSettled([
        api("/api/medicines"),
        api("/api/inventory")
    ]);
    if (medRes.status === "fulfilled") {
        allMedicines = toArray(medRes.value);
    }
    if (invRes.status === "fulfilled") {
        allInventory = toArray(invRes.value);
    }
    populateMedicineSelect();
}

function applySearch() {
    const q = document.getElementById("search").value.trim().toLowerCase();
    filtered = !q
        ? [...sales]
        : sales.filter(s => [s.medicine?.name ?? s.medicine_name ?? "", s.customer_name ?? ""].some(v => String(v).toLowerCase().includes(q)));
    render(filtered);
}

async function load() {
    const [salesRes, medRes, invRes] = await Promise.allSettled([
        api("/api/sales"),
        api("/api/medicines"),
        api("/api/inventory")
    ]);

    sales = salesRes.status === "fulfilled" ? toArray(salesRes.value) : [];
    allMedicines = medRes.status === "fulfilled" ? toArray(medRes.value) : [];
    allInventory = invRes.status === "fulfilled" ? toArray(invRes.value) : [];

    populateMedicineSelect();
    applySearch();

    if (medRes.status === "rejected" && invRes.status === "rejected") {
        showToast("Unable to load medicines and inventory. Check database/migrations.", "error");
    } else if (medRes.status === "rejected") {
        showToast("Medicines API failed. Using inventory data as fallback.", "warning");
    } else if (invRes.status === "rejected") {
        showToast("Inventory API failed. Sales may have limited stock checks.", "warning");
    }
}

function render(rows) {
    document.getElementById("rec-count").textContent = `Showing ${rows.length} transaction${rows.length !== 1 ? "s" : ""}`;
    document.getElementById("summary-count").textContent = rows.length;
    document.getElementById("summary-units").textContent = rows.reduce((a, s) => a + Number(s.quantity || 0), 0);
    document.getElementById("summary-revenue").textContent = fmtMoney(rows.reduce((a, s) => a + Number(s.total_amount || 0), 0));

    const tbody = document.getElementById("sales-tbody");
    tbody.innerHTML = rows.map((s, i) => `<tr class="tbl-row border-t border-gray-50">
        <td class="px-6 py-4 text-xs text-gray-400">${i + 1}</td>
        <td class="px-6 py-4 font-semibold text-gray-800">${escHtml(s.medicine?.name ?? s.medicine_name ?? "Unknown")}</td>
        <td class="px-6 py-4 text-gray-600">${escHtml(s.customer_name || "Walk-in")}</td>
        <td class="px-6 py-4 text-right font-medium text-gray-800">${s.quantity}</td>
        <td class="px-6 py-4 text-right text-gray-600">${fmtMoney(s.unit_price || 0)}</td>
        <td class="px-6 py-4 text-right font-bold text-[#b651f0]">${fmtMoney(s.total_amount || 0)}</td>
        <td class="px-6 py-4 text-gray-500">${fmtDate(s.created_at)}</td>
        <td class="px-6 py-4 text-center"><div class="inline-flex items-center gap-1.5">
            <button onclick="editSale(${s.id})" class="h-8 w-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition" title="Edit"><i class="fas fa-pencil text-xs"></i></button>
            <button onclick="deleteSale(${s.id})" class="h-8 w-8 flex items-center justify-center rounded-lg bg-red-50 text-red-500 hover:bg-red-100 transition" title="Delete"><i class="fas fa-trash text-xs"></i></button>
        </div></td>
    </tr>`).join("");
}

function onMedChange(sel) {
    const opt = sel.selectedOptions[0];
    document.getElementById("f-price").value = opt?.dataset?.price ?? "";
    calcTotal();

    const info = document.getElementById("stock-info");
    const inv = allInventory.find(i => Number(i.medicine_id) === Number(sel.value));
    if (!inv) {
        info.textContent = "No inventory record found.";
        info.className = "mt-1.5 text-xs text-gray-400 block";
        return;
    }

    info.textContent = `Available stock: ${inv.quantity} unit${Number(inv.quantity) !== 1 ? "s" : ""}`;
    info.className = "mt-1.5 text-xs block " +
        (Number(inv.quantity) === 0 ? "text-red-500 font-bold" : Number(inv.quantity) <= Number(inv.reorder_level) ? "text-amber-600 font-bold" : "text-gray-400");
}

function calcTotal() {
    const qty = Number(document.getElementById("f-qty").value || 0);
    const unit = Number(document.getElementById("f-price").value || 0);
    document.getElementById("f-total").value = fmtMoney(qty * unit);
}

async function openModal() {
    await refreshCatalogForModal();
    editSaleId = null;
    document.getElementById("modal-title").textContent = "Record New Sale";
    document.getElementById("submit-btn").textContent = "Record Sale";
    document.getElementById("f-medicine").value = "";
    document.getElementById("f-customer").value = "";
    document.getElementById("f-qty").value = "";
    document.getElementById("f-price").value = "";
    document.getElementById("f-total").value = "";
    document.getElementById("stock-info").className = "mt-1.5 text-xs text-gray-400 hidden";
    document.getElementById("modal").classList.remove("hidden");
}

function editSale(id) {
    const sale = sales.find(s => s.id === id);
    if (!sale) return;

    editSaleId = id;
    document.getElementById("modal-title").textContent = "Edit Sale";
    document.getElementById("submit-btn").textContent = "Update";
    document.getElementById("f-medicine").value = sale.medicine_id;
    document.getElementById("f-customer").value = sale.customer_name || "";
    document.getElementById("f-qty").value = sale.quantity;
    document.getElementById("f-price").value = sale.unit_price;
    document.getElementById("f-total").value = fmtMoney(sale.total_amount || 0);
    onMedChange(document.getElementById("f-medicine"));
    document.getElementById("modal").classList.remove("hidden");
}

async function deleteSale(id) {
    if (!confirm("Delete this sale record?")) return;
    try {
        await api(`/api/sales/${id}`, "DELETE");
        showToast("Sale deleted.", "warning");
        await load();
    } catch (e) {
        showToast(e.message, "error");
    }
}

function closeModal() {
    document.getElementById("modal").classList.add("hidden");
}

async function handleSubmit(e) {
    e.preventDefault();
    const btn = document.getElementById("submit-btn");
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner spin mr-1"></i> Saving...';

    const body = {
        medicine_id: Number(document.getElementById("f-medicine").value),
        customer_name: document.getElementById("f-customer").value.trim() || "Walk-in",
        quantity: Number(document.getElementById("f-qty").value)
    };

    try {
        if (editSaleId) {
            await api(`/api/sales/${editSaleId}`, "PUT", body);
            showToast("Sale updated.");
        } else {
            await api("/api/sales", "POST", body);
            showToast("Sale recorded.");
        }
        closeModal();
        await load();
    } catch (e) {
        showToast(e.message, "error");
    } finally {
        btn.disabled = false;
        btn.textContent = editSaleId ? "Update" : "Record Sale";
    }
}

document.getElementById("modal").addEventListener("click", e => {
    if (e.target === document.getElementById("modal")) closeModal();
});
document.getElementById("search").addEventListener("input", applySearch);

load();
JS);
