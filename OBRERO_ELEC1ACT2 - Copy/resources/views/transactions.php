<?php
require_once __DIR__ . '/lib/layout.php';

return Layout::build('Transactions', 'Transactions', 'transactions', function (DOMElement $main): void {
    $main->appendChild(Dom::el('h2', ['class' => 'text-xl font-bold mb-4']));
    $main->lastChild->appendChild(Dom::text('Transactions'));
    // Create Transaction Form
    $form = Dom::el('form', ['id' => 'transaction-form', 'class' => 'mb-6 p-4 bg-white rounded-xl shadow', 'onsubmit' => 'return createTransaction(event)']);
    $form->appendChild(Dom::el('h3', ['class' => 'font-bold mb-2']))->appendChild(Dom::text('Add Transaction'));
    $form->appendChild(Dom::el('input', ['type' => 'text', 'name' => 'user_id', 'placeholder' => 'User ID', 'class' => 'ring-inp border px-3 py-2 rounded mb-2 w-full', 'required' => 'required']));
    $form->appendChild(Dom::el('input', ['type' => 'text', 'name' => 'type', 'placeholder' => 'Type (payment, booking, reservation)', 'class' => 'ring-inp border px-3 py-2 rounded mb-2 w-full', 'required' => 'required']));
    $form->appendChild(Dom::el('input', ['type' => 'number', 'name' => 'amount', 'placeholder' => 'Amount', 'class' => 'ring-inp border px-3 py-2 rounded mb-2 w-full', 'required' => 'required', 'step' => '0.01']));
    $form->appendChild(Dom::el('input', ['type' => 'text', 'name' => 'status', 'placeholder' => 'Status', 'class' => 'ring-inp border px-3 py-2 rounded mb-2 w-full']));
    $form->appendChild(Dom::el('textarea', ['name' => 'details', 'placeholder' => 'Details', 'class' => 'ring-inp border px-3 py-2 rounded mb-2 w-full']));
    $form->appendChild(Dom::el('button', ['type' => 'submit', 'class' => 'bg-violet-600 text-white px-4 py-2 rounded hover:bg-violet-700']))->appendChild(Dom::text('Add'));
    $main->appendChild($form);
    $search = Dom::el('input', [
        'id' => 'transaction-search',
        'type' => 'text',
        'placeholder' => 'Search type, status, details...',
        'class' => 'ring-inp border px-3 py-2 rounded mb-2 w-full',
    ]);
    $main->appendChild($search);

    // Transactions Table
    $tbl = Dom::el('div', ['id' => 'transactions-table', 'class' => 'mt-4']);
    $main->appendChild($tbl);
}, <<<'JS'
let transactions = [];
let editId = null;

function applySearch() {
    const q = document.getElementById('transaction-search').value.trim().toLowerCase();
    const rows = !q
        ? [...transactions]
        : transactions.filter(t => [
            t.type ?? t.transaction_type ?? '',
            t.status ?? t.payment_method ?? '',
            t.details ?? t.notes ?? '',
            String(t.user_id),
            String(t.amount ?? t.total_price ?? ''),
        ].some(v => String(v).toLowerCase().includes(q)));
    renderTransactions(rows);
}

function renderTransactions(rows) {
    document.getElementById('transactions-table').innerHTML =
        `<table class='w-full text-sm'><thead><tr><th>ID</th><th>User</th><th>Type</th><th>Amount</th><th>Status</th><th>Details</th><th>Actions</th></tr></thead><tbody>` +
        rows.map(t => `<tr>
            <td>${t.id}</td>
            <td>${t.user_id}</td>
            <td>${escHtml(t.type ?? t.transaction_type ?? '-')}</td>
            <td>${Number(t.amount ?? t.total_price ?? 0).toFixed(2)}</td>
            <td>${escHtml(t.status ?? t.payment_method ?? '-')}</td>
            <td>${escHtml(t.details ?? t.notes ?? '-')}</td>
            <td>
                <button onclick='editTransaction(${t.id})' class='h-8 w-8 mr-1 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition'><i class='fas fa-pencil text-xs'></i></button>
                <button onclick='deleteTransaction(${t.id})' class='h-8 w-8 rounded-lg bg-red-50 text-red-500 hover:bg-red-100 transition'><i class='fas fa-trash text-xs'></i></button>
            </td>
        </tr>`).join('') +
        `</tbody></table>`;
}

async function loadTransactions() {
    const data = await api('/api/transactions');
    transactions = (data.data ?? data ?? []);
    applySearch();
}

function editTransaction(id) {
    const t = transactions.find(row => row.id === id);
    if (!t) return;
    editId = id;
    const form = document.getElementById('transaction-form');
    form.user_id.value = t.user_id;
    form.type.value = t.type ?? t.transaction_type ?? '';
    form.amount.value = t.amount ?? t.total_price ?? '';
    form.status.value = t.status ?? t.payment_method ?? '';
    form.details.value = t.details ?? t.notes ?? '';
    form.querySelector('button[type="submit"]').textContent = 'Update';
}

async function deleteTransaction(id) {
    if (!confirm('Delete this transaction?')) return;
    await api(`/api/transactions/${id}`, 'DELETE');
    showToast('Transaction deleted.', 'warning');
    loadTransactions();
}

async function createTransaction(e) {
    e.preventDefault();
    const form = document.getElementById('transaction-form');
    const fd = new FormData(form);
    const data = Object.fromEntries(fd.entries());
    if (editId) {
        await api(`/api/transactions/${editId}`, 'PUT', data);
    } else {
        await api('/api/transactions', 'POST', data);
    }
    form.reset();
    editId = null;
    form.querySelector('button[type="submit"]').textContent = 'Add';
    loadTransactions();
    showToast('Transaction saved!');
    return false;
}
document.getElementById('transaction-search').addEventListener('input', applySearch);
loadTransactions();
JS);
