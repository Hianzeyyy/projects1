<?php
require_once __DIR__ . '/lib/layout.php';

return Layout::build('Suppliers', 'Suppliers', 'suppliers', function (DOMElement $main): void {

    $card = Dom::el('div', ['class' => 'bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden']);

    $hdr  = Dom::el('div', ['class' => 'px-6 py-5 border-b border-gray-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4']);
    $td   = Dom::el('div');
    $h2   = Dom::el('h2', ['class' => 'font-bold text-gray-800']);
    $h2->appendChild(Dom::text('Supplier Directory'));
    $sb   = Dom::el('p', ['class' => 'text-xs text-gray-400 mt-0.5']);
    $sb->appendChild(Dom::text('Manage pharmaceutical suppliers'));
    $td->appendChild($h2); $td->appendChild($sb); $hdr->appendChild($td);

    $ctrl     = Dom::el('div', ['class' => 'flex items-center gap-3 w-full sm:w-auto']);
    $srchWrap = Dom::el('div', ['class' => 'relative flex-1 sm:w-64']);
    $si       = Dom::el('i',   ['class' => 'fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none']);
    $si->appendChild(Dom::text(''));
    $srchWrap->appendChild($si);
    $srchWrap->appendChild(Dom::el('input', [
        'id' => 'search', 'type' => 'text', 'placeholder' => 'Search suppliers...',
        'class' => 'ring-inp w-full pl-9 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-gray-50 focus:bg-white',
    ]));
    $ctrl->appendChild($srchWrap);
    $addBtn = Dom::el('button', ['onclick' => 'openModal()', 'class' => 'flex items-center gap-2 px-4 py-2.5 bg-green-600 text-white text-sm font-bold rounded-xl hover:bg-green-700 transition shadow-sm whitespace-nowrap']);
    $pi = Dom::el('i', ['class' => 'fas fa-plus text-xs']); $pi->appendChild(Dom::text('')); $addBtn->appendChild($pi);
    $addBtn->appendChild(Dom::text(' Add Supplier')); $ctrl->appendChild($addBtn);
    $hdr->appendChild($ctrl); $card->appendChild($hdr);

    // table
    $tw  = Dom::el('div', ['class' => 'overflow-x-auto']);
    $tbl = Dom::el('table', ['class' => 'w-full text-sm']);
    $th  = Dom::el('thead');
    $tr  = Dom::el('tr', ['class' => 'bg-gray-50 border-b border-gray-100 text-[11px] font-bold text-gray-400 uppercase tracking-wider']);
    foreach ([['#','text-left px-6 py-3.5'],['Supplier','text-left px-6 py-3.5'],['Email','text-left px-6 py-3.5'],['Phone','text-left px-6 py-3.5'],['Address','text-left px-6 py-3.5'],['Actions','text-center px-6 py-3.5']] as [$l,$c]) {
        $thEl = Dom::el('th', ['class' => $c]); $thEl->appendChild(Dom::text($l)); $tr->appendChild($thEl); }
    $th->appendChild($tr); $tbl->appendChild($th);
    $tbody = Dom::el('tbody', ['id' => 'suppliers-tbody']);
    $lr = Dom::el('tr'); $ltd = Dom::el('td', ['colspan' => '6', 'class' => 'text-center py-12 text-gray-300']);
    $spi = Dom::el('i', ['class' => 'fas fa-spinner spin mr-2']); $spi->appendChild(Dom::text(''));
    $ltd->appendChild($spi); $ltd->appendChild(Dom::text('Loading...')); $lr->appendChild($ltd); $tbody->appendChild($lr);
    $tbl->appendChild($tbody); $tw->appendChild($tbl); $card->appendChild($tw);
    $ft = Dom::el('div', ['class' => 'px-6 py-3 border-t border-gray-100 bg-gray-50']);
    $rc = Dom::el('p', ['id' => 'rec-count', 'class' => 'text-xs text-gray-400']); $rc->appendChild(Dom::text("\xE2\x80\x94"));
    $ft->appendChild($rc); $card->appendChild($ft); $main->appendChild($card);

    // modal
    $modal = Dom::el('div', ['id' => 'modal', 'class' => 'hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40']);
    $mbox  = Dom::el('div', ['class' => 'bg-white rounded-2xl shadow-2xl w-full max-w-lg']);
    $mh = Dom::el('div', ['class' => 'px-6 py-5 border-b border-gray-100 flex items-center justify-between']);
    $mt = Dom::el('h3', ['id' => 'modal-title', 'class' => 'text-lg font-bold text-gray-800']); $mt->appendChild(Dom::text(''));
    $xb = Dom::el('button', ['onclick' => 'closeModal()', 'class' => 'h-8 w-8 flex items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100 transition']);
    $xi = Dom::el('i', ['class' => 'fas fa-times text-sm']); $xi->appendChild(Dom::text('')); $xb->appendChild($xi);
    $mh->appendChild($mt); $mh->appendChild($xb); $mbox->appendChild($mh);
    $frm = Dom::el('form', ['id' => 'modal-form', 'class' => 'px-6 py-5 space-y-4', 'onsubmit' => 'handleSubmit(event)']);
    $frm->appendChild(Dom::el('input', ['type' => 'hidden', 'id' => 'f-id']));
    $inp = function (string $id, string $lbl, string $type, array $ex = [], bool $req = false) {
        $w = Dom::el('div'); $lb = Dom::el('label', ['class' => 'block text-xs font-bold text-gray-600 mb-1.5', 'for' => $id]);
        $lb->appendChild(Dom::text($lbl));
        if ($req) { $st = Dom::el('span', ['class' => 'text-red-500']); $st->appendChild(Dom::text(' *')); $lb->appendChild($st); }
        $w->appendChild($lb);
        $a = array_merge(['id'=>$id,'type'=>$type,'class'=>'ring-inp w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm'], $req ? ['required'=>'required'] : [], $ex);
        $w->appendChild(Dom::el('input', $a)); return $w;
    };
    $frm->appendChild($inp('f-name', 'Supplier Name', 'text', ['placeholder' => 'MedSupply Inc.'], true));
    $g1 = Dom::el('div', ['class' => 'grid grid-cols-2 gap-4']);
    $g1->appendChild($inp('f-email', 'Email', 'email', ['placeholder' => 'contact@supplier.com'], true));
    $g1->appendChild($inp('f-phone', 'Phone', 'text',  ['placeholder' => '+1 555 000 1234']));
    $frm->appendChild($g1);
    $aw = Dom::el('div'); $alb = Dom::el('label', ['class' => 'block text-xs font-bold text-gray-600 mb-1.5']); $alb->appendChild(Dom::text('Address'));
    $aw->appendChild($alb);
    $ata = Dom::el('textarea', ['id' => 'f-address', 'rows' => '2', 'placeholder' => 'Street, City, Country...', 'class' => 'ring-inp w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm resize-none']);
    $ata->appendChild(Dom::text('')); $aw->appendChild($ata); $frm->appendChild($aw);
    $br = Dom::el('div', ['class' => 'flex gap-3 pt-1']);
    $cb = Dom::el('button', ['type' => 'button', 'onclick' => 'closeModal()', 'class' => 'flex-1 py-2.5 border border-gray-200 text-gray-600 text-sm font-bold rounded-xl hover:bg-gray-50 transition']); $cb->appendChild(Dom::text('Cancel'));
    $sb2 = Dom::el('button', ['type' => 'submit', 'id' => 'submit-btn', 'class' => 'flex-1 py-2.5 bg-green-600 text-white text-sm font-bold rounded-xl hover:bg-green-700 transition shadow-sm']); $sb2->appendChild(Dom::text('Save'));
    $br->appendChild($cb); $br->appendChild($sb2); $frm->appendChild($br);
    $mbox->appendChild($frm); $modal->appendChild($mbox); $main->appendChild($modal);

}, <<<'JS'
let suppliers = [], editId = null;
function avatarColor(name) {
    const c = ["bg-violet-500","bg-blue-500","bg-teal-500","bg-orange-500","bg-pink-500","bg-indigo-500"];
    let h = 0; for (const ch of name) h = (h * 31 + ch.charCodeAt(0)) & 0xffffffff;
    return c[Math.abs(h) % c.length];
}
async function load() { try { suppliers = await api("/api/suppliers"); render(suppliers); } catch(e) { showToast(e.message, "error"); } }
function render(data) {
    document.getElementById("rec-count").textContent = `Showing ${data.length} supplier${data.length!==1?"s":""}`;
    const tbody = document.getElementById("suppliers-tbody");
    if (!data.length) { tbody.innerHTML = '<tr><td colspan="6" class="text-center py-12 text-gray-400">No suppliers found.</td></tr>'; return; }
    tbody.innerHTML = data.map((s, i) => `<tr class="tbl-row border-t border-gray-50">
        <td class="px-6 py-4 text-xs text-gray-400">${i+1}</td>
        <td class="px-6 py-4"><div class="flex items-center gap-3">
            <span class="h-9 w-9 rounded-xl ${avatarColor(s.name||"")} text-white font-bold text-sm flex items-center justify-center">${(s.name||"?")[0].toUpperCase()}</span>
            <span class="font-semibold text-gray-800">${escHtml(s.name)}</span></div></td>
        <td class="px-6 py-4 text-gray-600">${escHtml(s.email)}</td>
        <td class="px-6 py-4 text-gray-600">${escHtml(s.phone||"—")}</td>
        <td class="px-6 py-4 text-gray-500 text-sm max-w-[180px] truncate">${escHtml(s.address||"—")}</td>
        <td class="px-6 py-4 text-center"><div class="inline-flex items-center gap-1.5">
            <button onclick="editSup(${s.id})" class="h-8 w-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition"><i class="fas fa-pencil text-xs"></i></button>
            <button onclick="delSup(${s.id})"  class="h-8 w-8 flex items-center justify-center rounded-lg bg-red-50 text-red-500 hover:bg-red-100 transition"><i class="fas fa-trash text-xs"></i></button>
        </div></td></tr>`).join("");
}
document.getElementById("search").addEventListener("input", function () {
    const q = this.value.toLowerCase();
    render(!q ? suppliers : suppliers.filter(s => [s.name,s.email,s.phone??"",s.address??""].some(f => f.toLowerCase().includes(q))));
});
function openModal(s = null) {
    editId = s?.id ?? null;
    document.getElementById("modal-title").textContent = s ? "Edit Supplier" : "Add New Supplier";
    document.getElementById("submit-btn").textContent  = s ? "Update" : "Save";
    document.getElementById("f-id").value      = s?.id ?? "";
    document.getElementById("f-name").value    = s?.name ?? "";
    document.getElementById("f-email").value   = s?.email ?? "";
    document.getElementById("f-phone").value   = s?.phone ?? "";
    document.getElementById("f-address").value = s?.address ?? "";
    document.getElementById("modal").classList.remove("hidden");
    setTimeout(() => document.getElementById("f-name").focus(), 60);
}
function closeModal() { document.getElementById("modal").classList.add("hidden"); }
document.getElementById("modal").addEventListener("click", e => { if (e.target === document.getElementById("modal")) closeModal(); });
function editSup(id) { openModal(suppliers.find(s => s.id === id)); }
async function delSup(id) {
    if (!confirm("Delete this supplier?")) return;
    try { await api(`/api/suppliers/${id}`, "DELETE"); showToast("Deleted.", "warning"); load(); } catch(e) { showToast(e.message, "error"); }
}
async function handleSubmit(e) {
    e.preventDefault();
    const btn = document.getElementById("submit-btn");
    btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner spin mr-1"></i> Saving...';
    const body = { name: document.getElementById("f-name").value.trim(), email: document.getElementById("f-email").value.trim(), phone: document.getElementById("f-phone").value.trim(), address: document.getElementById("f-address").value.trim() };
    try {
        if (editId) { await api(`/api/suppliers/${editId}`, "PUT", body); showToast("Updated!"); }
        else        { await api("/api/suppliers", "POST", body);          showToast("Added!"); }
        closeModal(); load();
    } catch(e) { showToast(e.message, "error"); }
    finally { btn.disabled = false; btn.textContent = editId ? "Update" : "Save"; }
}
load();
JS);
