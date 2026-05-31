import './bootstrap';

function initSplashRedirect() {
    const splash = document.getElementById('splash-card');
    if (!splash) {
        return;
    }

    setTimeout(function() {
        const isAuth = splash.getAttribute('data-auth') === '1';
        window.location.href = isAuth ? '/dashboard' : '/login';
    }, 3000);
}

function peso(value) {
    return `PHP ${Number(value || 0).toFixed(2)}`;
}

function parseJsonResponse(response) {
    return response.json().catch(function() {
        return {};
    });
}

function safeParseJson(value, fallback) {
    try {
        return JSON.parse(value);
    } catch (_error) {
        return fallback;
    }
}

function buildInventoryOptions(inventory) {
    const inStock = (Array.isArray(inventory) ? inventory : [])
        .filter(function(item) {
            return Number(item.quantity || 0) > 0;
        })
        .sort(function(a, b) {
            return String(a.medicine_name || '').localeCompare(String(b.medicine_name || ''));
        });

    if (!inStock.length) {
        return '<option value="" disabled>No stock left in inventory</option>';
    }

    return inStock
        .map(function(item) {
            return `<option value="${item.id}" data-stock="${item.quantity}" data-medicine-id="${item.medicine_id}">${item.medicine_name} (Qty left: ${item.quantity})</option>`;
        })
        .join('');
}

function createTransactionRow(inventory, medicines, defaults) {
    const wrapper = document.createElement('div');
    wrapper.className = 'tx-row';
    wrapper.style.display = 'grid';
    wrapper.style.gridTemplateColumns = 'minmax(220px, 2.2fr) minmax(260px, 2.6fr) minmax(120px, 1.1fr) minmax(160px, 1.3fr) 96px';
    wrapper.style.minWidth = '860px';
    wrapper.style.gap = '0.75rem';
    wrapper.style.alignItems = 'center';
    wrapper.innerHTML = `
        <div>
            <input type="text" value="${defaults?.drug_name || defaults?.medicine_name || ''}" class="phx-input tx-drug" placeholder="Drug Name">
        </div>
        <div>
            <select class="phx-input tx-inventory" required>
                <option value="">Select medicine</option>
                ${buildInventoryOptions(inventory)}
            </select>
        </div>
        <div style="min-width: 120px;">
            <input type="number" min="1" value="${defaults?.quantity || 1}" class="phx-input tx-qty" placeholder="Qty" required>
        </div>
        <div style="min-width: 160px;">
            <input type="number" min="0" step="0.01" value="${defaults?.unit_price || ''}" class="phx-input tx-price" placeholder="Unit Price">
        </div>
        <div style="display:flex;align-items:center;justify-content:flex-end;">
            <button type="button" class="tx-remove rounded-xl border border-violet-200 text-sm font-semibold text-violet-700 hover:bg-violet-50" style="min-width: 88px; height: 48px;">Remove</button>
        </div>
    `;

    if (defaults && (defaults.drug_name || defaults.medicine_name)) {
        const select = wrapper.querySelector('.tx-inventory');
        const targetName = String(defaults.drug_name || defaults.medicine_name).toLowerCase();
        const found = inventory.find(function(item) {
            return item.medicine_name.toLowerCase().includes(targetName) || targetName.includes(item.medicine_name.toLowerCase());
        });
        if (found) {
            select.value = String(found.id);
        }
    }

    return wrapper;
}

function normalizeInventoryPayload(payload) {
    const rows = Array.isArray(payload) ?
        payload :
        (Array.isArray(payload && payload.data) ? payload.data : []);

    return rows.map(function(item) {
        return {
            id: item.id,
            medicine_id: item.medicine_id,
            quantity: Number(item.quantity || 0),
            medicine_name: item.medicine_name || (item.medicine && item.medicine.name) || ('Medicine #' + item.id),
        };
    });
}

async function fetchLiveInventory() {
    const response = await fetch('/api/inventory', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
        },
        credentials: 'same-origin',
    });

    const payload = await parseJsonResponse(response);
    if (!response.ok) {
        throw new Error(payload.message || 'Failed to load live inventory.');
    }

    return normalizeInventoryPayload(payload);
}

function collectTransactionItems(rows, inventory) {
    const items = [];
    let error = '';

    rows.querySelectorAll('.tx-row').forEach(function(row) {
        const selected = row.querySelector('.tx-inventory').value;
        const quantity = row.querySelector('.tx-qty').value;
        const unitPrice = row.querySelector('.tx-price').value;

        if (!selected || !quantity) {
            return;
        }

        let inventoryId = null;
        if (selected.startsWith('medicine:')) {
            const medicineId = Number(selected.split(':')[1]);
            const found = inventory.find(function(inv) {
                return Number(inv.medicine_id) === medicineId;
            });

            if (!found) {
                const selectedOption = row.querySelector('.tx-inventory').selectedOptions[0];
                const medName = selectedOption ? selectedOption.textContent : 'Selected medicine';
                error = `${medName} has no inventory stock record. Please add stock in Inventory first.`;
                return;
            }

            inventoryId = Number(found.id);
        } else {
            inventoryId = Number(selected);
        }

        items.push({
            inventory_id: Number(inventoryId),
            quantity: Number(quantity),
            unit_price: unitPrice === '' ? null : Number(unitPrice),
        });
    });

    return { items: items, error: error };
}

function updateEstimatedTotal(form, rows, display) {
    const taxRate = Number(form.querySelector('[name="tax_rate"]').value || 0);
    let subtotal = 0;

    rows.querySelectorAll('.tx-row').forEach(function(row) {
        const qty = Number(row.querySelector('.tx-qty').value || 0);
        const price = Number(row.querySelector('.tx-price').value || 0);
        subtotal += qty * price;
    });

    const tax = subtotal * (taxRate / 100);
    const total = subtotal + tax;
    display.textContent = peso(total);
}

function initPharmacyModule() {
    const parserForm = document.getElementById('ai-parser-form');
    const txSection = document.getElementById('transaction-builder');
    const txForm = document.getElementById('pharmacy-transaction-form');
    const rows = document.getElementById('tx-rows');
    const addRow = document.getElementById('tx-add-row');
    const parsedOutput = document.getElementById('ai-parser-output');
    const estimatedTotal = document.getElementById('tx-estimated-total');
    const receiptLink = document.getElementById('receipt-download-link');

    if (!parserForm || !txSection || !txForm || !rows || !addRow || !parsedOutput || !estimatedTotal || !receiptLink) {
        return;
    }

    let inventory = safeParseJson(rows.dataset.inventory || '[]', []);
    const medicines = safeParseJson(rows.dataset.medicines || '[]', []);
    const parserButton = parserForm.querySelector('button[type="submit"]');

    function refreshRowSelectOptions(preserveValue) {
        rows.querySelectorAll('.tx-inventory').forEach(function(select) {
            const currentValue = select.value;
            select.innerHTML = '<option value="">Select medicine</option>' + buildInventoryOptions(inventory);

            if (preserveValue && currentValue) {
                const stillExists = Array.from(select.options).some(function(opt) {
                    return String(opt.value) === String(currentValue);
                });
                if (stillExists) {
                    select.value = currentValue;
                }
            }
        });
    }

    async function syncInventoryLive(preserveValue) {
        try {
            inventory = await fetchLiveInventory();
            refreshRowSelectOptions(preserveValue);
        } catch (_error) {
            // Keep existing UI values when live sync is temporarily unavailable.
        }
    }

    function addDefaultRow(defaults) {
        rows.appendChild(createTransactionRow(inventory, medicines, defaults));
        updateEstimatedTotal(txForm, rows, estimatedTotal);
    }

    addDefaultRow();
    syncInventoryLive(true);

    addRow.addEventListener('click', async function() {
        await syncInventoryLive(true);
        addDefaultRow();
    });

    rows.addEventListener('focusin', async function(event) {
        if (!event.target.classList.contains('tx-inventory')) {
            return;
        }
        await syncInventoryLive(true);
    });

    rows.addEventListener('click', function(event) {
        const removeButton = event.target.closest('.tx-remove');
        if (!removeButton) {
            return;
        }

        const allRows = rows.querySelectorAll('.tx-row');
        if (allRows.length === 1) {
            return;
        }

        removeButton.closest('.tx-row').remove();
        updateEstimatedTotal(txForm, rows, estimatedTotal);
    });

    rows.addEventListener('input', function() {
        updateEstimatedTotal(txForm, rows, estimatedTotal);
    });
    txForm.querySelector('[name="tax_rate"]').addEventListener('input', function() {
        updateEstimatedTotal(txForm, rows, estimatedTotal);
    });

    parserForm.addEventListener('submit', async function(event) {
        event.preventDefault();
        const fd = new FormData(parserForm);
        const token = parserForm.querySelector('input[name="_token"]').value;
        const rawText = String(fd.get('raw_text') || '').trim();
        const imageFile = fd.get('prescription_image');

        if (!rawText && (!(imageFile instanceof File) || imageFile.size === 0)) {
            alert('Please provide raw text or upload a prescription image.');
            return;
        }

        const payload = new FormData();
        payload.append('_token', token);
        if (rawText) {
            payload.append('raw_text', rawText);
        }
        if (imageFile instanceof File && imageFile.size > 0) {
            payload.append('prescription_image', imageFile);
        }

        try {
            if (parserButton) {
                parserButton.disabled = true;
                parserButton.textContent = 'Parsing...';
            }
            parsedOutput.textContent = 'Parsing prescription...';

            const response = await fetch(parserForm.dataset.parseUrl, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token,
                },
                credentials: 'same-origin',
                body: payload,
            });

            const result = await parseJsonResponse(response);
            if (!response.ok) {
                parsedOutput.textContent = JSON.stringify(result, null, 2);
                alert(result.message || 'Failed to parse prescription.');
                return;
            }

            parsedOutput.textContent = JSON.stringify(result, null, 2);
            if (result.warning) {
                alert(result.warning);
            }

            rows.innerHTML = '';
            const items = Array.isArray(result.items) ? result.items : [];
            if (items.length === 0) {
                addDefaultRow();
                return;
            }

            items.forEach(function(item) {
                addDefaultRow(item);
            });
        } catch (error) {
            parsedOutput.textContent = JSON.stringify({ message: error.message || 'Unexpected parser error.' }, null, 2);
            alert('Unable to parse right now. Please try again.');
        } finally {
            if (parserButton) {
                parserButton.disabled = false;
                parserButton.textContent = 'Parse Prescription';
            }
        }
    });

    txForm.addEventListener('submit', async function(event) {
        event.preventDefault();
        const token = txForm.querySelector('input[name="_token"]').value;
        await syncInventoryLive(true);
        const collected = collectTransactionItems(rows, inventory);

        if (collected.error) {
            alert(collected.error);
            return;
        }

        const items = collected.items;
        if (!items.length) {
            alert('Please select at least one medicine row with quantity.');
            return;
        }

        const payload = {
            transaction_type: txForm.querySelector('[name="transaction_type"]').value,
            payment_method: txForm.querySelector('[name="payment_method"]').value,
            tax_rate: Number(txForm.querySelector('[name="tax_rate"]').value || 0),
            notes: txForm.querySelector('[name="notes"]').value,
            items: items,
        };

        const response = await fetch(txSection.dataset.storeUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': token,
            },
            body: JSON.stringify(payload),
        });

        const result = await parseJsonResponse(response);

        if (!response.ok) {
            alert(result.message || 'Failed to save transaction.');
            return;
        }

        const transactionId = result.transaction && result.transaction.id;
        if (transactionId) {
            receiptLink.href = `${txSection.dataset.receiptUrlBase}/${transactionId}/receipt`;
            receiptLink.classList.remove('hidden');
            receiptLink.textContent = 'Print / Download Receipt';
            window.open(receiptLink.href, '_blank');
        }

        // Keep dropdown stock in sync immediately after save without full page refresh.
        items.forEach(function(item) {
            const inv = inventory.find(function(row) {
                return Number(row.id) === Number(item.inventory_id);
            });
            if (!inv) {
                return;
            }

            inv.quantity = Math.max(0, Number(inv.quantity || 0) - Number(item.quantity || 0));
        });

        alert('Transaction saved and inventory updated.');
        rows.innerHTML = '';
        addDefaultRow();
        txForm.querySelector('[name="notes"]').value = '';
        updateEstimatedTotal(txForm, rows, estimatedTotal);

        await syncInventoryLive(true);
    });

    // Keep selector stock relatively fresh even without user interaction.
    setInterval(function() {
        syncInventoryLive(true);
    }, 15000);

    document.addEventListener('visibilitychange', function() {
        if (document.visibilityState === 'visible') {
            syncInventoryLive(true);
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    initSplashRedirect();
    initPharmacyModule();
});