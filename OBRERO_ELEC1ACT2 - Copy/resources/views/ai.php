<?php
require_once __DIR__ . '/lib/layout.php';

$pageJS = <<<'JS'
let aiRows = [];
async function loadAI() {
    const data = await api('/api/aiprocesses');
    aiRows = (data.data ?? data ?? []);
    const sampleRows = [
        {id: 301, user_id: 1, form_type: 'prescription_parser', status: 'completed', input_data: 'Amoxicillin 500mg 2 capsules daily', result: '{"items":[{"medicine_name":"Amoxicillin 500mg","quantity":2}]}'},
        {id: 302, user_id: 1, form_type: 'smart_search', status: 'completed', input_data: 'Find antihistamine for allergy', result: '{"items":[{"medicine_name":"Cetirizine 10mg","quantity":1}]}'},
    ];
    const list = aiRows.length ? aiRows : sampleRows;
    document.getElementById('ai-table').innerHTML =
        `<table class='w-full text-sm'><thead><tr><th>ID</th><th>User</th><th>Form Type</th><th>Status</th><th>Input</th><th>Result</th><th>Actions</th></tr></thead><tbody>` +
        list.map(a => `<tr><td>${a.id}</td><td>${a.user_id}</td><td>${a.form_type}</td><td>${a.status}</td><td>${a.input_data||''}</td><td>${a.result||''}</td><td><button onclick='editAI(${a.id})' class='h-8 w-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition mr-1'><i class='fas fa-pencil text-xs'></i></button><button onclick='deleteAI(${a.id})' class='h-8 w-8 flex items-center justify-center rounded-lg bg-red-50 text-red-500 hover:bg-red-100 transition'><i class='fas fa-trash text-xs'></i></button></td></tr>`).join('') +
        `</tbody></table>`;
}
async function createAI(e) {
    e.preventDefault();
    const form = document.getElementById('ai-form');
    const fd = new FormData(form);
    const data = Object.fromEntries(fd.entries());
    await api('/api/aiprocesses', 'POST', data);
    form.reset();
    loadAI();
}
function editAI(id) {
    const row = aiRows.find(a => a.id === id);
    document.getElementById('ai-edit-id').value = row.id;
    document.getElementById('ai-edit-user').value = row.user_id;
    document.getElementById('ai-edit-formtype').value = row.form_type;
    document.getElementById('ai-edit-status').value = row.status;
    document.getElementById('ai-edit-input').value = row.input_data;
    document.getElementById('ai-edit-result').value = row.result;
    document.getElementById('ai-edit-modal').classList.remove('hidden');
}
function closeAiEditModal() { document.getElementById('ai-edit-modal').classList.add('hidden'); }
async function handleAiEdit(e) {
    e.preventDefault();
    const btn = document.getElementById('ai-edit-submit');
    btn.disabled = true;
    btn.innerText = 'Saving...';
    const id = document.getElementById('ai-edit-id').value;
    const body = {
        user_id: document.getElementById('ai-edit-user').value,
        form_type: document.getElementById('ai-edit-formtype').value,
        status: document.getElementById('ai-edit-status').value,
        input_data: document.getElementById('ai-edit-input').value,
        result: document.getElementById('ai-edit-result').value,
    };
    try {
        await api(`/api/aiprocesses/${id}`, 'PUT', body);
        showToast('Updated!', 'success');
        closeAiEditModal();
        loadAI();
    } catch(e) {
        showToast(e.message, 'error');
    }
    btn.disabled = false;
    btn.innerText = 'Save';
}
async function deleteAI(id) {
    if (!confirm('Delete this AI process?')) return;
    try { await api(`/api/aiprocesses/${id}`, 'DELETE'); showToast('Deleted.', 'warning'); loadAI(); } catch(e) { showToast(e.message, 'error'); }
}
// Ensure loadAI runs on page load
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', loadAI);
} else {
    loadAI();
}
JS;

return Layout::build(
    'Information and Records',
    'Information and Records',
    'ai',
    function (DOMElement $main): void {
        $main->appendChild(Dom::el('h2', ['class' => 'text-xl font-bold mb-4']));
        $main->lastChild->appendChild(Dom::text('AI Process'));
        // Create AI Process Form
        $form = Dom::el('form', ['id' => 'ai-form', 'class' => 'mb-6 p-4 bg-white rounded-xl shadow', 'onsubmit' => 'return createAI(event)']);
        $form->appendChild(Dom::el('h3', ['class' => 'font-bold mb-2']))->appendChild(Dom::text('Submit AI Request'));
        $form->appendChild(Dom::el('input', ['type' => 'text', 'name' => 'user_id', 'placeholder' => 'User ID', 'class' => 'ring-inp border px-3 py-2 rounded mb-2 w-full', 'required' => 'required']));
        $form->appendChild(Dom::el('input', ['type' => 'text', 'name' => 'form_type', 'placeholder' => 'Form Type (prediction, classification, etc.)', 'class' => 'ring-inp border px-3 py-2 rounded mb-2 w-full', 'required' => 'required']));
        $form->appendChild(Dom::el('textarea', ['name' => 'input_data', 'placeholder' => 'Input Data', 'class' => 'ring-inp border px-3 py-2 rounded mb-2 w-full', 'required' => 'required']));
        $form->appendChild(Dom::el('input', ['type' => 'text', 'name' => 'status', 'placeholder' => 'Status', 'class' => 'ring-inp border px-3 py-2 rounded mb-2 w-full']));
        $form->appendChild(Dom::el('button', ['type' => 'submit', 'class' => 'bg-violet-600 text-white px-4 py-2 rounded hover:bg-violet-700']))->appendChild(Dom::text('Submit'));
        $main->appendChild($form);
        // AI Table
        $tbl = Dom::el('div', ['id' => 'ai-table', 'class' => 'mt-4']);
        $main->appendChild($tbl);
        // Edit Modal
        $editModal = Dom::el('div', ['id' => 'ai-edit-modal', 'class' => 'hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40']);
        $mbox = Dom::el('div', ['class' => 'bg-white rounded-2xl shadow-2xl w-full max-w-lg']);
        $mh = Dom::el('div', ['class' => 'px-6 py-5 border-b border-gray-100 flex items-center justify-between']);
        $mt = Dom::el('h3', ['id' => 'ai-edit-title', 'class' => 'text-lg font-bold text-gray-800']); $mt->appendChild(Dom::text('Edit AI Process'));
        $xb = Dom::el('button', ['onclick' => 'closeAiEditModal()', 'class' => 'h-8 w-8 flex items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100 transition']);
        $xi = Dom::el('i', ['class' => 'fas fa-times text-sm']); $xi->appendChild(Dom::text('')); $xb->appendChild($xi);
        $mh->appendChild($mt); $mh->appendChild($xb); $mbox->appendChild($mh);
        $frm = Dom::el('form', ['id' => 'ai-edit-form', 'class' => 'px-6 py-5 space-y-4', 'onsubmit' => 'return handleAiEdit(event)']);
        $frm->appendChild(Dom::el('input', ['type' => 'hidden', 'id' => 'ai-edit-id']));
        $frm->appendChild(Dom::el('input', ['type' => 'text', 'id' => 'ai-edit-user', 'placeholder' => 'User ID', 'class' => 'ring-inp w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm', 'required' => 'required']));
        $frm->appendChild(Dom::el('input', ['type' => 'text', 'id' => 'ai-edit-formtype', 'placeholder' => 'Form Type', 'class' => 'ring-inp w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm', 'required' => 'required']));
        $frm->appendChild(Dom::el('input', ['type' => 'text', 'id' => 'ai-edit-status', 'placeholder' => 'Status', 'class' => 'ring-inp w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm']));
        $frm->appendChild(Dom::el('textarea', ['id' => 'ai-edit-input', 'placeholder' => 'Input Data', 'class' => 'ring-inp w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm', 'required' => 'required']));
        $frm->appendChild(Dom::el('textarea', ['id' => 'ai-edit-result', 'placeholder' => 'Result', 'class' => 'ring-inp w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm']));
        $br = Dom::el('div', ['class' => 'flex gap-3 pt-1']);
        $cb = Dom::el('button', ['type' => 'button', 'onclick' => 'closeAiEditModal()', 'class' => 'flex-1 py-2.5 border border-gray-200 text-gray-600 text-sm font-bold rounded-xl hover:bg-gray-50 transition']); $cb->appendChild(Dom::text('Cancel'));
        $sb = Dom::el('button', ['type' => 'submit', 'id' => 'ai-edit-submit', 'class' => 'flex-1 py-2.5 bg-[#b651f0] text-white text-sm font-bold rounded-xl hover:bg-[#a040c0] transition shadow-sm']); $sb->appendChild(Dom::text('Save'));
        $br->appendChild($cb); $br->appendChild($sb); $frm->appendChild($br);
        $mbox->appendChild($frm); $editModal->appendChild($mbox); $main->appendChild($editModal);
    },
    $pageJS
);
