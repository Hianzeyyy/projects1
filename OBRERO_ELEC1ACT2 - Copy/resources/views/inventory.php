<?php
require_once __DIR__ . '/lib/layout.php';

return Layout::build('Inventory', 'Inventory', 'inventory', function (DOMElement $main): void {

    $card = Dom::el('div', ['class' => 'bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden']);

    // header
    $hdr  = Dom::el('div', ['class' => 'px-6 py-5 border-b border-gray-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4']);
    $td   = Dom::el('div');
    $h2   = Dom::el('h2', ['class' => 'font-bold text-gray-800']); $h2->appendChild(Dom::text('Stock Management'));
    $sb   = Dom::el('p',  ['class' => 'text-xs text-gray-400 mt-0.5']); $sb->appendChild(Dom::text('Track inventory levels and reorder points'));
    $td->appendChild($h2); $td->appendChild($sb); $hdr->appendChild($td);

    $ctrl = Dom::el('div', ['class' => 'flex items-center gap-3 w-full sm:w-auto']);
    $srchWrap = Dom::el('div', ['class' => 'relative flex-1 sm:w-64']);
    $srchI    = Dom::el('i', ['class' => 'fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none']);
    $srchI->appendChild(Dom::text(''));
    $srchWrap->appendChild($srchI);
    $srchWrap->appendChild(Dom::el('input', [
        'id' => 'search',
        'type' => 'text',
        'placeholder' => 'Search medicine, supplier, batch...',
        'class' => 'ring-inp w-full pl-9 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-gray-50 focus:bg-white',
    ]));
    $ctrl->appendChild($srchWrap);
    $fsel = Dom::el('select', ['id' => 'filter', 'class' => 'ring-inp px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm bg-gray-50 focus:bg-white']);
    foreach (['' => 'All Stock', 'out' => 'Out of Stock', 'low' => 'Low Stock', 'ok' => 'In Stock'] as $v => $l) {
        $o = Dom::el('option', ['value' => $v]); $o->appendChild(Dom::text($l)); $fsel->appendChild($o); }
    $ctrl->appendChild($fsel);
    $addBtn = Dom::el('button', ['id' => 'add-item-btn', 'type' => 'button', 'class' => 'flex items-center gap-2 px-4 py-2.5 bg-[#b651f0] text-white text-sm font-bold rounded-xl hover:bg-[#a040c0] transition shadow-sm whitespace-nowrap']);
    $pi = Dom::el('i', ['class' => 'fas fa-plus text-xs']); $pi->appendChild(Dom::text('')); $addBtn->appendChild($pi); $addBtn->appendChild(Dom::text(' Add Item'));
    $ctrl->appendChild($addBtn); $hdr->appendChild($ctrl); $card->appendChild($hdr);

    // table
    $tw  = Dom::el('div', ['class' => 'overflow-x-auto']);
    $tbl = Dom::el('table', ['class' => 'w-full text-sm']);
    $th  = Dom::el('thead');
    $tr  = Dom::el('tr', ['class' => 'bg-gray-50 border-b border-gray-100 text-[11px] font-bold text-gray-400 uppercase tracking-wider']);
    foreach ([['#','text-left px-6 py-3.5'],['Medicine','text-left px-6 py-3.5'],['Supplier','text-left px-6 py-3.5'],['Qty','text-right px-6 py-3.5'],['Reorder','text-right px-6 py-3.5'],['Batch','text-left px-6 py-3.5'],['Expiry','text-left px-6 py-3.5'],['Status','text-left px-6 py-3.5'],['Actions','text-center px-6 py-3.5']] as [$l,$c]) {
        $te = Dom::el('th', ['class' => $c]); $te->appendChild(Dom::text($l)); $tr->appendChild($te); }
    $th->appendChild($tr); $tbl->appendChild($th);
    $tbody = Dom::el('tbody', ['id' => 'inventory-tbody']);
        $tbl->appendChild($tbody); 
        $tw->appendChild($tbl); 
        $card->appendChild($tw);
    $ft = Dom::el('div', ['class' => 'px-6 py-3 border-t border-gray-100 bg-gray-50']);
    $rc = Dom::el('p', ['id' => 'rec-count', 'class' => 'text-xs text-gray-400']); $rc->appendChild(Dom::text("\xE2\x80\x94"));
    $ft->appendChild($rc); $card->appendChild($ft); $main->appendChild($card);

    // modal
    $modal = Dom::el('div', ['id' => 'modal', 'class' => 'hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40']);
    $mbox  = Dom::el('div', ['class' => 'bg-white rounded-2xl shadow-2xl w-full max-w-lg']);
    $mh = Dom::el('div', ['class' => 'px-6 py-5 border-b border-gray-100 flex items-center justify-between']);
    $mt = Dom::el('h3', ['id' => 'modal-title', 'class' => 'text-lg font-bold text-gray-800']); $mt->appendChild(Dom::text(''));
    $xb = Dom::el('button', ['id' => 'modal-close-btn', 'type' => 'button', 'class' => 'h-8 w-8 flex items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100 transition']);
    $xi = Dom::el('i', ['class' => 'fas fa-times text-sm']); $xi->appendChild(Dom::text('')); $xb->appendChild($xi);
    $mh->appendChild($mt); $mh->appendChild($xb); $mbox->appendChild($mh);
    $frm = Dom::el('form', ['id' => 'modal-form', 'class' => 'px-6 py-5 space-y-4']);
    $frm->appendChild(Dom::el('input', ['type' => 'hidden', 'id' => 'f-id']));

    // select helper
    $sel = function (string $id, string $lbl, string $placeholder, bool $req = false) {
        $w  = Dom::el('div');
        $lb = Dom::el('label', ['class' => 'block text-xs font-bold text-gray-600 mb-1.5', 'for' => $id]);
        $lb->appendChild(Dom::text($lbl));
        if ($req) { $st = Dom::el('span', ['class' => 'text-red-500']); $st->appendChild(Dom::text(' *')); $lb->appendChild($st); }
        $w->appendChild($lb);
        $sl = Dom::el('select', array_merge(['id' => $id, 'class' => 'ring-inp w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm'], $req ? ['required' => 'required'] : []));
        $do = Dom::el('option', ['value' => '']); $do->appendChild(Dom::text($placeholder)); $sl->appendChild($do);
        $w->appendChild($sl); return $w;
    };
    $frm->appendChild($sel('f-medicine', 'Medicine', 'Select medicine...', true));
    $frm->appendChild($sel('f-supplier', 'Supplier', 'Select supplier...', true));

    $inp = function (string $id, string $lbl, string $type, array $ex = [], bool $req = false) {
        $w  = Dom::el('div');
        $lb = Dom::el('label', ['class' => 'block text-xs font-bold text-gray-600 mb-1.5', 'for' => $id]);
        $lb->appendChild(Dom::text($lbl));
        if ($req) { $st = Dom::el('span', ['class' => 'text-red-500']); $st->appendChild(Dom::text(' *')); $lb->appendChild($st); }
        $w->appendChild($lb);
        $a = array_merge(['id' => $id, 'type' => $type, 'class' => 'ring-inp w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm'], $req ? ['required' => 'required'] : [], $ex);
        $w->appendChild(Dom::el('input', $a)); return $w;
    };

    $g1 = Dom::el('div', ['class' => 'grid grid-cols-2 gap-4']);
    $g1->appendChild($inp('f-quantity', 'Quantity',     'number', ['min' => '0', 'placeholder' => '0'],   true));
    $g1->appendChild($inp('f-reorder',  'Reorder Level','number', ['min' => '0', 'placeholder' => '10']));
    $frm->appendChild($g1);
    $g2 = Dom::el('div', ['class' => 'grid grid-cols-2 gap-4']);
    $g2->appendChild($inp('f-batch',  'Batch Number', 'text', ['placeholder' => 'BATCH-001']));
    $g2->appendChild($inp('f-expiry', 'Expiry Date',  'date'));
    $frm->appendChild($g2);

    $br = Dom::el('div', ['class' => 'flex gap-3 pt-1']);
    $cb = Dom::el('button', ['id' => 'modal-cancel-btn', 'type' => 'button', 'class' => 'flex-1 py-2.5 border border-gray-200 text-gray-600 text-sm font-bold rounded-xl hover:bg-gray-50 transition']); $cb->appendChild(Dom::text('Cancel'));
    $sb = Dom::el('button', ['type' => 'submit', 'id' => 'submit-btn', 'class' => 'flex-1 py-2.5 bg-[#b651f0] text-white text-sm font-bold rounded-xl hover:bg-[#a040c0] transition shadow-sm']); $sb->appendChild(Dom::text('Save'));
    $br->appendChild($cb); $br->appendChild($sb); $frm->appendChild($br);
    $mbox->appendChild($frm); $modal->appendChild($mbox); $main->appendChild($modal);

}, <<<'JS'
let inventory = [], medicines = [], suppliers = [], editId = null;
async function load() {
    try {
        const [inventoryRes, medicinesRes, suppliersRes] = await Promise.all([api("/api/inventory"), api("/api/medicines"), api("/api/suppliers")]);
        inventory = inventoryRes?.data ?? inventoryRes ?? [];
        medicines = medicinesRes?.data ?? medicinesRes ?? [];
        suppliers = suppliersRes?.data ?? suppliersRes ?? [];
        document.getElementById("f-medicine").innerHTML = '<option value="">Select medicine...</option>' + medicines.map(m => `<option value="${m.id}">${escHtml(m.name)}</option>`).join("");
        document.getElementById("f-supplier").innerHTML = '<option value="">Select supplier...</option>' + suppliers.map(s => `<option value="${s.id}">${escHtml(s.name)}</option>`).join("");
        render(applyFilters());
    } catch(e) {
        inventory = [];
        render([]);
        showToast(e.message, "error");
    }
}
function statusBadge(qty, ro) {
    if (Number(qty) === 0) return '<span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-red-100 text-red-700">Out of Stock</span>';
    if (Number(qty) <= Number(ro)) return '<span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-amber-100 text-amber-700">Low Stock</span>';
    return '<span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-[#e9d3fa] text-[#b651f0]">In Stock</span>';
}
function applyFilters() {
    const f = document.getElementById("filter").value;
    const q = document.getElementById("search").value.trim().toLowerCase();
    return inventory.filter(i => {
        const qty = Number(i.quantity);
        const ro = Number(i.reorder_level);
        const passStatus = !f || (f === "out" && qty === 0) || (f === "low" && qty > 0 && qty <= ro) || (f === "ok" && qty > ro);
        const hay = [i.medicine?.name ?? "", i.supplier?.name ?? "", i.batch_number ?? "", i.medicine_name ?? "", i.supplier_name ?? ""].join(" ").toLowerCase();
        const passSearch = !q || hay.includes(q);
        return passStatus && passSearch;
    });
}
function render(data) {
    document.getElementById("rec-count").textContent = `Showing ${data.length} item${data.length!==1?"s":""}`;
    const tbody = document.getElementById("inventory-tbody");
    if (!data.length) {
        tbody.innerHTML = `<tr class="tbl-row border-t border-gray-50"><td colspan="9" class="px-6 py-8 text-center text-sm text-gray-500">No inventory records found.</td></tr>`;
        return;
    }
    const pct = i => Math.min(100, i.reorder_level > 0 ? Math.round(i.quantity / Math.max(i.quantity, i.reorder_level * 2) * 100) : 100);
    tbody.innerHTML = data.map((item, idx) => `<tr class="tbl-row border-t border-gray-50">
        <td class="px-6 py-4 text-xs text-gray-400">${idx+1}</td>
        <td class="px-6 py-4 font-semibold text-gray-800">${escHtml(item.medicine?.name ?? "—")}</td>
        <td class="px-6 py-4 text-gray-600 text-sm">${escHtml(item.supplier?.name ?? "—")}</td>
        <td class="px-6 py-4 text-right">
            <p class="font-bold text-gray-800">${item.quantity}</p>
            <div class="w-16 h-1.5 rounded-full bg-gray-100 ml-auto mt-1"><div class="h-full rounded-full ${Number(item.quantity)===0?"bg-red-400":Number(item.quantity)<=Number(item.reorder_level)?"bg-amber-400":"bg-[#b651f0]"}" style="width:${pct(item)}%"></div></div>
        </td>
        <td class="px-6 py-4 text-right text-gray-600">${item.reorder_level}</td>
        <td class="px-6 py-4 text-gray-500 text-sm">${escHtml(item.batch_number || "—")}</td>
        <td class="px-6 py-4 text-sm text-gray-500">${fmtDate(item.expiry_date)}</td>
        <td class="px-6 py-4">${statusBadge(item.quantity, item.reorder_level)}</td>
        <td class="px-6 py-4 text-center"><div class="inline-flex items-center gap-2">
            <button type="button" data-action="edit" data-id="${item.id}" class="h-10 w-10 flex items-center justify-center rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-100 transition"><i class="fas fa-pencil text-sm"></i></button>
            <button type="button" data-action="delete" data-id="${item.id}" class="h-10 w-10 flex items-center justify-center rounded-xl bg-red-50 text-red-500 hover:bg-red-100 transition"><i class="fas fa-trash text-sm"></i></button>
        </div></td></tr>`).join("");
}
document.getElementById("filter").addEventListener("change", () => render(applyFilters()));
document.getElementById("search").addEventListener("input", () => render(applyFilters()));
document.getElementById("add-item-btn").addEventListener("click", () => openModal());
document.getElementById("modal-close-btn").addEventListener("click", closeModal);
document.getElementById("modal-cancel-btn").addEventListener("click", closeModal);
document.getElementById("modal-form").addEventListener("submit", handleSubmit);
document.getElementById("inventory-tbody").addEventListener("click", (event) => {
    const btn = event.target.closest("button[data-action][data-id]");
    if (!btn) return;
    const id = Number(btn.dataset.id);
    if (!Number.isFinite(id)) return;
    if (btn.dataset.action === "edit") {
        editItem(id);
        return;
    }
    if (btn.dataset.action === "delete") {
        delItem(id);
    }
});
function openModal(item = null) {
    editId = item?.id ?? null;
    document.getElementById("modal-title").textContent = item ? "Edit Inventory" : "Add Inventory Item";
    document.getElementById("submit-btn").textContent  = item ? "Update" : "Save";
    document.getElementById("f-id").value       = item?.id ?? "";
    document.getElementById("f-medicine").value = item?.medicine_id ?? "";
    document.getElementById("f-supplier").value = item?.supplier_id ?? "";
    document.getElementById("f-quantity").value = item?.quantity ?? "";
    document.getElementById("f-reorder").value  = item?.reorder_level ?? "";
    document.getElementById("f-batch").value    = item?.batch_number ?? "";
    document.getElementById("f-expiry").value   = item ? toInputDate(item.expiry_date) : "";
    document.getElementById("modal").classList.remove("hidden");
    setTimeout(() => document.getElementById("f-medicine").focus(), 60);
}
function closeModal() { document.getElementById("modal").classList.add("hidden"); }
document.getElementById("modal").addEventListener("click", e => { if (e.target === document.getElementById("modal")) closeModal(); });
function editItem(id) {
    const item = inventory.find(i => Number(i.id) === Number(id));
    if (!item) {
        showToast("Inventory item not found.", "error");
        return;
    }
    openModal(item);
}
async function delItem(id) {
    if (!confirm("Delete this record?")) return;
    try {
        await api(`/api/inventory/${id}`, "DELETE");
        showToast("Deleted.", "warning");
        await load();
    } catch(e) {
        const message = String(e.message || "").toLowerCase();
        if (message.includes("constraint") || message.includes("foreign")) {
            showToast("Cannot delete: inventory record is linked to transaction records.", "error");
            return;
        }
        showToast(e.message, "error");
    }
}
async function handleSubmit(e) {
    e.preventDefault();
    const btn = document.getElementById("submit-btn");
    btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner spin mr-1"></i> Saving...';

    const current = editId ? inventory.find(i => i.id === editId) : null;
    const selectedSupplier = document.getElementById("f-supplier").value;
    const body = {
        medicine_id: parseInt(document.getElementById("f-medicine").value),
        supplier_id: selectedSupplier
            ? parseInt(selectedSupplier)
            : (current?.supplier_id ?? null),
        quantity: parseInt(document.getElementById("f-quantity").value),
        reorder_level: parseInt(document.getElementById("f-reorder").value) || Number(current?.reorder_level ?? 10),
        batch_number: document.getElementById("f-batch").value.trim() || current?.batch_number || null,
        expiry_date: document.getElementById("f-expiry").value || (current?.expiry_date ? toInputDate(current.expiry_date) : null),
    };
    try {
        if (editId) { await api(`/api/inventory/${editId}`, "PUT", body); showToast("Updated!"); }
        else        { await api("/api/inventory", "POST", body);          showToast("Added!"); }
        closeModal(); load();
    } catch(e) { showToast(e.message, "error"); }
    finally { btn.disabled = false; btn.textContent = editId ? "Update" : "Save"; }
}
load();
JS);
