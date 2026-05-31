<?php
require_once __DIR__ . '/lib/layout.php';

$pageJS = <<<'JS'
let reportRows = [];
let filteredRows = [];

function applyReportSearch() {
    const q = document.getElementById('report-search').value.trim().toLowerCase();
    filteredRows = !q
        ? [...reportRows]
        : reportRows.filter(r => [String(r.id), String(r.user_id), r.type ?? '', r.data ?? ''].some(v => String(v).toLowerCase().includes(q)));
    renderReports(filteredRows);
}

function renderReports(list) {
    document.getElementById('reports-table').innerHTML =
        `<table class='w-full text-sm'><thead><tr><th>ID</th><th>User</th><th>Type</th><th>Generated</th><th>Data</th><th>Action</th></tr></thead><tbody>` +
        list.map(r => `<tr><td>${r.id}</td><td>${r.user_id}</td><td>${escHtml(r.type)}</td><td>${escHtml(r.generated_at||'')}</td><td>${escHtml(r.data||'')}</td><td><a href='/reports/${r.id}/download' class='px-3 py-1 bg-violet-600 text-white rounded hover:bg-violet-700 transition' target='_blank'>Download PDF</a> <button onclick='editReport(${r.id})' class='h-8 w-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition ml-1'><i class='fas fa-pencil text-xs'></i></button><button onclick='deleteReport(${r.id})' class='h-8 w-8 flex items-center justify-center rounded-lg bg-red-50 text-red-500 hover:bg-red-100 transition ml-1'><i class='fas fa-trash text-xs'></i></button></td></tr>`).join('') +
        `</tbody></table>`;
}

async function loadReports() {
    const data = await api('/api/reports');
    reportRows = (data.data ?? data ?? []);
    const sampleRows = [
        {id: 201, user_id: 1, type: 'daily', generated_at: new Date().toISOString(), data: 'Sample daily sales snapshot'},
        {id: 202, user_id: 1, type: 'weekly', generated_at: new Date().toISOString(), data: 'Sample weekly trend report'}
    ];
    const list = reportRows.length ? reportRows : sampleRows;
    reportRows = list;
    applyReportSearch();
}
async function createReport(e) {
    e.preventDefault();
    const form = document.getElementById('report-form');
    const fd = new FormData(form);
    const data = Object.fromEntries(fd.entries());
    await api('/api/reports', 'POST', data);
    form.reset();
    loadReports();
}
function editReport(id) {
    const row = reportRows.find(r => r.id === id);
    document.getElementById('report-edit-id').value = row.id;
    document.getElementById('report-edit-user').value = row.user_id;
    document.getElementById('report-edit-type').value = row.type;
    document.getElementById('report-edit-generated').value = row.generated_at ? row.generated_at.substr(0,10) : '';
    document.getElementById('report-edit-data').value = row.data;
    document.getElementById('report-edit-modal').classList.remove('hidden');
}
function closeReportEditModal() { document.getElementById('report-edit-modal').classList.add('hidden'); }
async function handleReportEdit(e) {
    e.preventDefault();
    const btn = document.getElementById('report-edit-submit');
    btn.disabled = true;
    btn.innerText = 'Saving...';
    const id = document.getElementById('report-edit-id').value;
    const body = {
        user_id: document.getElementById('report-edit-user').value,
        type: document.getElementById('report-edit-type').value,
        generated_at: document.getElementById('report-edit-generated').value,
        data: document.getElementById('report-edit-data').value,
    };
    try {
        await api(`/api/reports/${id}`, 'PUT', body);
        showToast('Updated!', 'success');
        closeReportEditModal();
        loadReports();
    } catch(e) {
        showToast(e.message, 'error');
    }
    btn.disabled = false;
    btn.innerText = 'Save';
}
async function deleteReport(id) {
    if (!confirm('Delete this report?')) return;
    try { await api(`/api/reports/${id}`, 'DELETE'); showToast('Deleted.', 'warning'); loadReports(); } catch(e) { showToast(e.message, 'error'); }
}
if (document.getElementById('report-search')) {
    document.getElementById('report-search').addEventListener('input', applyReportSearch);
}
// Ensure loadReports runs on page load
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', loadReports);
} else {
    loadReports();
}
JS;

return Layout::build(
    'Information and Records',
    'Information and Records',
    'reports',
    function (DOMElement $main): void {
        $main->appendChild(Dom::el('h2', ['class' => 'text-xl font-bold mb-4']));
        $main->lastChild->appendChild(Dom::text('Reports'));
        // Create Report Form
        $form = Dom::el('form', ['id' => 'report-form', 'class' => 'mb-6 p-4 bg-white rounded-xl shadow', 'onsubmit' => 'return createReport(event)']);
        $form->appendChild(Dom::el('h3', ['class' => 'font-bold mb-2']))->appendChild(Dom::text('Generate Report'));
        $form->appendChild(Dom::el('input', ['type' => 'text', 'name' => 'user_id', 'placeholder' => 'User ID', 'class' => 'ring-inp border px-3 py-2 rounded mb-2 w-full', 'required' => 'required']));
        $form->appendChild(Dom::el('input', ['type' => 'text', 'name' => 'type', 'placeholder' => 'Type (daily, weekly, monthly, receipt)', 'class' => 'ring-inp border px-3 py-2 rounded mb-2 w-full', 'required' => 'required']));
        $form->appendChild(Dom::el('input', ['type' => 'date', 'name' => 'generated_at', 'placeholder' => 'Generated At', 'class' => 'ring-inp border px-3 py-2 rounded mb-2 w-full']));
        $form->appendChild(Dom::el('textarea', ['name' => 'data', 'placeholder' => 'Report Data', 'class' => 'ring-inp border px-3 py-2 rounded mb-2 w-full', 'required' => 'required']));
        $form->appendChild(Dom::el('button', ['type' => 'submit', 'class' => 'bg-violet-600 text-white px-4 py-2 rounded hover:bg-violet-700']))->appendChild(Dom::text('Generate'));
        $main->appendChild($form);
        $main->appendChild(Dom::el('input', [
            'id' => 'report-search',
            'type' => 'text',
            'placeholder' => 'Search reports...',
            'class' => 'ring-inp border px-3 py-2 rounded mb-2 w-full',
        ]));
        // Reports Table
        $tbl = Dom::el('div', ['id' => 'reports-table', 'class' => 'mt-4']);
        $main->appendChild($tbl);
        // Edit Modal
        $editModal = Dom::el('div', ['id' => 'report-edit-modal', 'class' => 'hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40']);
        $mbox = Dom::el('div', ['class' => 'bg-white rounded-2xl shadow-2xl w-full max-w-lg']);
        $mh = Dom::el('div', ['class' => 'px-6 py-5 border-b border-gray-100 flex items-center justify-between']);
        $mt = Dom::el('h3', ['id' => 'report-edit-title', 'class' => 'text-lg font-bold text-gray-800']); $mt->appendChild(Dom::text('Edit Report'));
        $xb = Dom::el('button', ['onclick' => 'closeReportEditModal()', 'class' => 'h-8 w-8 flex items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100 transition']);
        $xi = Dom::el('i', ['class' => 'fas fa-times text-sm']); $xi->appendChild(Dom::text('')); $xb->appendChild($xi);
        $mh->appendChild($mt); $mh->appendChild($xb); $mbox->appendChild($mh);
        $frm = Dom::el('form', ['id' => 'report-edit-form', 'class' => 'px-6 py-5 space-y-4', 'onsubmit' => 'return handleReportEdit(event)']);
        $frm->appendChild(Dom::el('input', ['type' => 'hidden', 'id' => 'report-edit-id']));
        $frm->appendChild(Dom::el('input', ['type' => 'text', 'id' => 'report-edit-user', 'placeholder' => 'User ID', 'class' => 'ring-inp w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm', 'required' => 'required']));
        $frm->appendChild(Dom::el('input', ['type' => 'text', 'id' => 'report-edit-type', 'placeholder' => 'Type', 'class' => 'ring-inp w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm', 'required' => 'required']));
        $frm->appendChild(Dom::el('input', ['type' => 'date', 'id' => 'report-edit-generated', 'placeholder' => 'Generated At', 'class' => 'ring-inp w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm']));
        $frm->appendChild(Dom::el('textarea', ['id' => 'report-edit-data', 'placeholder' => 'Report Data', 'class' => 'ring-inp w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm', 'required' => 'required']));
        $br = Dom::el('div', ['class' => 'flex gap-3 pt-1']);
        $cb = Dom::el('button', ['type' => 'button', 'onclick' => 'closeReportEditModal()', 'class' => 'flex-1 py-2.5 border border-gray-200 text-gray-600 text-sm font-bold rounded-xl hover:bg-gray-50 transition']); $cb->appendChild(Dom::text('Cancel'));
        $sb = Dom::el('button', ['type' => 'submit', 'id' => 'report-edit-submit', 'class' => 'flex-1 py-2.5 bg-[#b651f0] text-white text-sm font-bold rounded-xl hover:bg-[#a040c0] transition shadow-sm']); $sb->appendChild(Dom::text('Save'));
        $br->appendChild($cb); $br->appendChild($sb); $frm->appendChild($br);
        $mbox->appendChild($frm); $editModal->appendChild($mbox); $main->appendChild($editModal);
    },
    $pageJS
);
