<?php
require_once __DIR__ . '/lib/layout.php';

return Layout::build('Medicines', 'Medicines', 'medicines', function (DOMElement $main): void {

    $card = Dom::el('div', ['class' => 'bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden']);

    // ── header bar ──────────────────────────────────────────────────────────
    $hdr = Dom::el('div', [
        'class' => 'px-6 py-5 border-b border-gray-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4',
    ]);

    $td = Dom::el('div');
    $h2 = Dom::el('h2', ['class' => 'font-bold text-gray-800']);
    $h2->appendChild(Dom::text('Medicine Registry'));
    $sb = Dom::el('p', ['class' => 'text-xs text-gray-400 mt-0.5']);
    $sb->appendChild(Dom::text('Manage all pharmaceutical products'));
    $td->appendChild($h2);
    $td->appendChild($sb);
    $hdr->appendChild($td);

    $ctrl = Dom::el('div', ['class' => 'flex items-center gap-3 w-full sm:w-auto']);

    $srchWrap = Dom::el('div', ['class' => 'relative flex-1 sm:w-64']);
    $srchI    = Dom::el('i',   ['class' => 'fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none']);
    $srchI->appendChild(Dom::text(''));
    $srchWrap->appendChild($srchI);
    $srchWrap->appendChild(Dom::el('input', [
        'id'          => 'search',
        'type'        => 'text',
        'placeholder' => 'Search by name, category...',
        'class'       => 'ring-inp w-full pl-9 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-gray-50 focus:bg-white',
    ]));
    $ctrl->appendChild($srchWrap);

    $addBtn = Dom::el('button', [
        'onclick' => 'openModal()',
        'class'   => 'flex items-center gap-2 px-4 py-2.5 bg-green-600 text-white text-sm font-bold rounded-xl hover:bg-green-700 transition shadow-sm whitespace-nowrap',
    ]);
    $plusI = Dom::el('i', ['class' => 'fas fa-plus text-xs']);
    $plusI->appendChild(Dom::text(''));
    $addBtn->appendChild($plusI);
    $addBtn->appendChild(Dom::text(' Add Medicine'));
    $ctrl->appendChild($addBtn);

    $hdr->appendChild($ctrl);
    $card->appendChild($hdr);

    // ── table ───────────────────────────────────────────────────────────────
    $tw  = Dom::el('div',   ['class' => 'overflow-x-auto']);
    $tbl = Dom::el('table', ['class' => 'w-full text-sm']);
    $th  = Dom::el('thead');
    $tr  = Dom::el('tr', ['class' => 'bg-gray-50 border-b border-gray-100 text-[11px] font-bold text-gray-400 uppercase tracking-wider']);
    foreach ([
        ['#',            'text-left px-6 py-3.5'],
        ['Name',         'text-left px-6 py-3.5'],
        ['Category',     'text-left px-6 py-3.5'],
        ['Manufacturer', 'text-left px-6 py-3.5'],
        ['Price',        'text-right px-6 py-3.5'],
        ['Expiry Date',  'text-left px-6 py-3.5'],
        ['Actions',      'text-center px-6 py-3.5'],
    ] as [$l, $c]) {
        $thEl = Dom::el('th', ['class' => $c]);
        $thEl->appendChild(Dom::text($l));
        $tr->appendChild($thEl);
    }
    $th->appendChild($tr);
    $tbl->appendChild($th);

    $tbody = Dom::el('tbody', ['id' => 'medicines-tbody']);
    $lr    = Dom::el('tr');
    $ltd   = Dom::el('td', ['colspan' => '7', 'class' => 'text-center py-12 text-gray-300']);
    $spi   = Dom::el('i', ['class' => 'fas fa-spinner spin mr-2']);
    $spi->appendChild(Dom::text(''));
    $ltd->appendChild($spi);
    $ltd->appendChild(Dom::text('Loading medicines...'));
    $lr->appendChild($ltd);
    $tbody->appendChild($lr);
    $tbl->appendChild($tbody);
    $tw->appendChild($tbl);
    $card->appendChild($tw);

    $ft = Dom::el('div', ['class' => 'px-6 py-3 border-t border-gray-100 bg-gray-50']);
    $rc = Dom::el('p',   ['id' => 'rec-count', 'class' => 'text-xs text-gray-400']);
    $rc->appendChild(Dom::text("\xE2\x80\x94"));
    $ft->appendChild($rc);
    $card->appendChild($ft);
    $main->appendChild($card);

    // ── modal ───────────────────────────────────────────────────────────────
    $modal = Dom::el('div', ['id' => 'modal', 'class' => 'hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40']);
    $mbox  = Dom::el('div', ['class' => 'bg-white rounded-2xl shadow-2xl w-full max-w-lg']);

    $mh  = Dom::el('div', ['class' => 'px-6 py-5 border-b border-gray-100 flex items-center justify-between']);
    $mt  = Dom::el('h3',  ['id' => 'modal-title', 'class' => 'text-lg font-bold text-gray-800']);
    $mt->appendChild(Dom::text(''));
    $xb  = Dom::el('button', ['onclick' => 'closeModal()', 'class' => 'h-8 w-8 flex items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100 transition']);
    $xi  = Dom::el('i', ['class' => 'fas fa-times text-sm']);
    $xi->appendChild(Dom::text(''));
    $xb->appendChild($xi);
    $mh->appendChild($mt);
    $mh->appendChild($xb);
    $mbox->appendChild($mh);

    $frm = Dom::el('form', ['id' => 'modal-form', 'class' => 'px-6 py-5 space-y-4', 'onsubmit' => 'handleSubmit(event)']);
    $frm->appendChild(Dom::el('input', ['type' => 'hidden', 'id' => 'f-id']));

    // Utility: labeled text/number/date input
    $inp = function (string $id, string $label, string $type, array $extra = [], bool $req = false) use ($frm): DOMElement {
        $w  = Dom::el('div');
        $lb = Dom::el('label', ['class' => 'block text-xs font-bold text-gray-600 mb-1.5', 'for' => $id]);
        $lb->appendChild(Dom::text($label));
        if ($req) {
            $st = Dom::el('span', ['class' => 'text-red-500']);
            $st->appendChild(Dom::text(' *'));
            $lb->appendChild($st);
        }
        $w->appendChild($lb);
        $attrs = array_merge([
            'id' => $id, 'type' => $type,
            'class' => 'ring-inp w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm',
        ], $req ? ['required' => 'required'] : [], $extra);
        $w->appendChild(Dom::el('input', $attrs));
        return $w;
    };

    $frm->appendChild($inp('f-name',         'Medicine Name', 'text',   ['placeholder' => 'e.g. Paracetamol 500mg'], true));
    $g1 = Dom::el('div', ['class' => 'grid grid-cols-2 gap-4']);
    $g1->appendChild($inp('f-category',    'Category',    'text',   ['placeholder' => 'e.g. Analgesic'],  true));
    $g1->appendChild($inp('f-manufacturer','Manufacturer','text',   ['placeholder' => 'e.g. PharmaCorp'], true));
    $frm->appendChild($g1);

    $g2 = Dom::el('div', ['class' => 'grid grid-cols-2 gap-4']);

    // Price field with $ prefix
    $pw  = Dom::el('div');
    $plb = Dom::el('label', ['class' => 'block text-xs font-bold text-gray-600 mb-1.5', 'for' => 'f-price']);
    $plb->appendChild(Dom::text('Price (USD)'));
    $pst = Dom::el('span', ['class' => 'text-red-500']);
    $pst->appendChild(Dom::text(' *'));
    $plb->appendChild($pst);
    $pw->appendChild($plb);
    $pwr = Dom::el('div', ['class' => 'relative']);
    $psp = Dom::el('span', ['class' => 'absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm']);
    $psp->appendChild(Dom::text('$'));
    $pwr->appendChild($psp);
    $pwr->appendChild(Dom::el('input', [
        'id' => 'f-price', 'type' => 'number', 'required' => 'required',
        'step' => '0.01', 'min' => '0', 'placeholder' => '0.00',
        'class' => 'ring-inp w-full pl-7 pr-3.5 py-2.5 border border-gray-200 rounded-xl text-sm',
    ]));
    $pw->appendChild($pwr);
    $g2->appendChild($pw);
    $g2->appendChild($inp('f-expiry', 'Expiry Date', 'date', [], true));
    $frm->appendChild($g2);

    // Description textarea
    $dw  = Dom::el('div');
    $dlb = Dom::el('label', ['class' => 'block text-xs font-bold text-gray-600 mb-1.5', 'for' => 'f-desc']);
    $dlb->appendChild(Dom::text('Description'));
    $dw->appendChild($dlb);
    $dta = Dom::el('textarea', ['id' => 'f-desc', 'rows' => '2', 'placeholder' => 'Optional...', 'class' => 'ring-inp w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm resize-none']);
    $dta->appendChild(Dom::text(''));
    $dw->appendChild($dta);
    $frm->appendChild($dw);

    // Buttons
    $br  = Dom::el('div', ['class' => 'flex gap-3 pt-1']);
    $cb  = Dom::el('button', ['type' => 'button', 'onclick' => 'closeModal()', 'class' => 'flex-1 py-2.5 border border-gray-200 text-gray-600 text-sm font-bold rounded-xl hover:bg-gray-50 transition']);
    $cb->appendChild(Dom::text('Cancel'));
    $sb  = Dom::el('button', ['type' => 'submit', 'id' => 'submit-btn', 'class' => 'flex-1 py-2.5 bg-green-600 text-white text-sm font-bold rounded-xl hover:bg-green-700 transition shadow-sm']);
    $sb->appendChild(Dom::text('Save'));
    $br->appendChild($cb);
    $br->appendChild($sb);
    $frm->appendChild($br);

    $mbox->appendChild($frm);
    $modal->appendChild($mbox);
    $main->appendChild($modal);

}, <<<'JS'
let medicines = [], editId = null;
const catPalette = {"Analgesic":"bg-blue-100 text-blue-700","Antibiotic":"bg-violet-100 text-violet-700","Antihistamine":"bg-teal-100 text-teal-700","Antiviral":"bg-orange-100 text-orange-700","Antifungal":"bg-pink-100 text-pink-700","Supplement":"bg-lime-100 text-lime-700","default":"bg-gray-100 text-gray-600"};
const catCls = c => catPalette[c] ?? catPalette["default"];
async function load() { try { medicines = await api("/api/medicines"); render(medicines); } catch(e) { showToast(e.message,"error"); } }
function render(data) {
    document.getElementById("rec-count").textContent = `Showing ${data.length} medicine${data.length!==1?"s":""}`;
    const tbody = document.getElementById("medicines-tbody");
    if (!data.length) { tbody.innerHTML = '<tr><td colspan="7" class="text-center py-12 text-gray-400">No medicines found.</td></tr>'; return; }
    tbody.innerHTML = data.map((m, idx) => {
        const days = Math.ceil((new Date(m.expiry_date) - Date.now()) / 86400000);
        const ec   = days < 0 ? "text-red-500" : days < 90 ? "text-orange-500" : "text-gray-500";
        return `<tr class="tbl-row border-t border-gray-50">
            <td class="px-6 py-4 text-xs text-gray-400">${idx+1}</td>
            <td class="px-6 py-4"><p class="font-semibold text-gray-800">${escHtml(m.name)}</p>${m.description ? `<p class="text-xs text-gray-400 mt-0.5 max-w-[180px] truncate">${escHtml(m.description)}</p>` : ""}</td>
            <td class="px-6 py-4"><span class="px-2.5 py-1 rounded-full text-[11px] font-bold ${catCls(m.category)}">${escHtml(m.category)}</span></td>
            <td class="px-6 py-4 text-gray-600">${escHtml(m.manufacturer)}</td>
            <td class="px-6 py-4 text-right font-bold text-green-700">${fmtMoney(m.price)}</td>
            <td class="px-6 py-4 text-sm ${ec}">${fmtDate(m.expiry_date)}${days<0?" <b>(Expired)</b>":days<90?` <b>(${days}d)</b>`:""}</td>
            <td class="px-6 py-4 text-center"><div class="inline-flex items-center gap-1.5">
                <button onclick="editMed(${m.id})" class="h-8 w-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition"><i class="fas fa-pencil text-xs"></i></button>
                <button onclick="delMed(${m.id})"  class="h-8 w-8 flex items-center justify-center rounded-lg bg-red-50 text-red-500 hover:bg-red-100 transition"><i class="fas fa-trash text-xs"></i></button>
            </div></td></tr>`;
    }).join("");
}
document.getElementById("search").addEventListener("input", function () {
    const q = this.value.toLowerCase();
    render(!q ? medicines : medicines.filter(m => [m.name, m.category, m.manufacturer, m.description ?? ""].some(f => f.toLowerCase().includes(q))));
});
function openModal(m = null) {
    editId = m?.id ?? null;
    document.getElementById("modal-title").textContent  = m ? "Edit Medicine" : "Add New Medicine";
    document.getElementById("submit-btn").textContent   = m ? "Update" : "Save";
    document.getElementById("f-id").value           = m?.id ?? "";
    document.getElementById("f-name").value         = m?.name ?? "";
    document.getElementById("f-category").value     = m?.category ?? "";
    document.getElementById("f-manufacturer").value = m?.manufacturer ?? "";
    document.getElementById("f-price").value        = m?.price ?? "";
    document.getElementById("f-expiry").value       = m ? toInputDate(m.expiry_date) : "";
    document.getElementById("f-desc").value         = m?.description ?? "";
    document.getElementById("modal").classList.remove("hidden");
    setTimeout(() => document.getElementById("f-name").focus(), 60);
}
function closeModal() { document.getElementById("modal").classList.add("hidden"); }
document.getElementById("modal").addEventListener("click", e => { if (e.target === document.getElementById("modal")) closeModal(); });
function editMed(id) { openModal(medicines.find(m => m.id === id)); }
async function delMed(id) {
    if (!confirm("Delete this medicine?")) return;
    try { await api(`/api/medicines/${id}`, "DELETE"); showToast("Deleted.", "warning"); load(); } catch(e) { showToast(e.message, "error"); }
}
async function handleSubmit(e) {
    e.preventDefault();
    const btn = document.getElementById("submit-btn");
    btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner spin mr-1"></i> Saving...';
    const body = { name: document.getElementById("f-name").value.trim(), category: document.getElementById("f-category").value.trim(), manufacturer: document.getElementById("f-manufacturer").value.trim(), price: parseFloat(document.getElementById("f-price").value), expiry_date: document.getElementById("f-expiry").value, description: document.getElementById("f-desc").value.trim() };
    try {
        if (editId) { await api(`/api/medicines/${editId}`, "PUT", body); showToast("Updated!"); }
        else        { await api("/api/medicines", "POST", body);          showToast("Added!"); }
        closeModal(); load();
    } catch(e) { showToast(e.message, "error"); }
    finally { btn.disabled = false; btn.textContent = editId ? "Update" : "Save"; }
}
load();
JS);
