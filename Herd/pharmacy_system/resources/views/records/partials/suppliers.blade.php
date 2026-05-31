

<div class="suppliers-modern-container" style="background:#fff;border-radius:18px;padding:2rem 2rem 2rem 2rem;box-shadow:0 4px 24px #a78bfa22,0 1.5px 8px #fff3;border:1.5px solid #ececec;width:100%;max-width:100vw;margin:0;">
    <div class="records-search-box" style="margin-bottom:2rem;width:100%;background:#f3f4f6;border-radius:12px;box-shadow:0 1px 4px #a78bfa11;padding:0.2rem 1rem;display:flex;align-items:center;">
        <span class="records-search-icon" aria-hidden="true" style="color:#9ca3af;font-size:1.2rem;margin-right:0.7rem;">&#128269;</span>
        <input type="text" placeholder="Search suppliers..." class="records-search-input" style="border:none;background:transparent;outline:none;font-size:1.08rem;color:#222;width:100%;padding:0.7rem 0;box-shadow:none;"/>
    </div>
    <div class="suppliers-card-grid" style="display:flex;flex-wrap:wrap;gap:2.5rem;width:100%;">
        <?php $supplierRows = collect($suppliers ?? []); ?>
        <?php $__empty = true; foreach ($supplierRows as $supplierRow): $__empty = false; ?>
            <div class="supplier-card-modern" style="background:#fff;border-radius:18px;box-shadow:0 2px 8px #a78bfa22;border:1.5px solid #ececec;padding:2rem;min-width:320px;max-width:420px;flex:1 1 320px;display:flex;flex-direction:column;gap:1.2rem;">
                <div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;">
                    <h2 style="font-size:1.5rem;font-weight:700;margin:0;"><?= e($supplierRow->name) ?></h2>
                    <div style="display:flex;align-items:center;gap:0.7rem;">
                        <a href="<?= e(route('suppliers.edit', $supplierRow)) ?>" title="Edit" style="color:#222;text-decoration:none;font-size:1.15rem;display:inline-flex;align-items:center;">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 1 1 3 3L7 19.5 3 21l1.5-4L16.5 3.5z"/></svg>
                        </a>
                        <form action="<?= e(route('suppliers.destroy', $supplierRow)) ?>" method="POST" style="display:inline;">
                            <?= csrf_field() ?>
                            <?= method_field('DELETE') ?>
                            <button type="submit" title="Delete" style="background:none;border:none;color:#dc2626;font-size:1.15rem;cursor:pointer;display:inline-flex;align-items:center;">
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
                <div style="display:flex;flex-direction:column;gap:0.7rem;">
                    <div style="display:flex;align-items:center;gap:0.7rem;color:#6b7280;font-size:1.08rem;">
                        <span style="min-width:1.5em;display:inline-flex;align-items:center;justify-content:center;">
                            <svg width="18" height="18" fill="none" stroke="#6b7280" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M4 4h16v16H4z"/><path d="M22 6l-10 7L2 6"/></svg>
                        </span>
                        <span><?= e($supplierRow->email ?: 'N/A') ?></span>
                    </div>
                    <div style="display:flex;align-items:center;gap:0.7rem;color:#6b7280;font-size:1.08rem;">
                        <span style="min-width:1.5em;display:inline-flex;align-items:center;justify-content:center;">
                            <svg width="18" height="18" fill="none" stroke="#6b7280" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M22 16.92V19a2 2 0 0 1-2 2A18 18 0 0 1 2 5a2 2 0 0 1 2-2h2.09a2 2 0 0 1 2 1.72c.13.81.36 1.6.7 2.34a2 2 0 0 1-.45 2.11L7.91 10.09a16 16 0 0 0 6 6l1.92-1.92a2 2 0 0 1 2.11-.45c.74.34 1.53.57 2.34.7A2 2 0 0 1 22 16.92z"/></svg>
                        </span>
                        <span><?= e($supplierRow->phone ?: 'N/A') ?></span>
                    </div>
                    <div style="display:flex;align-items:center;gap:0.7rem;color:#6b7280;font-size:1.08rem;">
                        <span style="min-width:1.5em;display:inline-flex;align-items:center;justify-content:center;">
                            <svg width="18" height="18" fill="none" stroke="#6b7280" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 12.414a4 4 0 1 0-1.414 1.414l4.243 4.243a1 1 0 0 0 1.414-1.414z"/><circle cx="11" cy="11" r="8"/></svg>
                        </span>
                        <span><?= e($supplierRow->address ?: 'N/A') ?></span>
                    </div>
                </div>
            </div>
        <?php endforeach; if ($__empty): ?>
            <div class="empty-state records-empty-span" style="text-align:center;padding:2rem 0;">
                <div class="empty-state-icon" style="font-size:2.5rem;">🤝</div>
                <h3 style="margin:1rem 0 0.5rem 0;">No suppliers found</h3>
                <p style="margin-bottom:1.2rem;">Get started by adding your first supplier.</p>
                <a href="<?= e(route('suppliers.create')) ?>" class="btn btn-primary">➕ Add Supplier</a>
            </div>
        <?php endif; ?>
    </div>
</div>
