// ===== MIS SYSTEM JS =====

document.addEventListener('DOMContentLoaded', function () {

  // ===== SIDEBAR TOGGLE =====
  const sidebar   = document.getElementById('sidebar');
  const backdrop  = document.getElementById('sidebar-backdrop');
  const menuBtn   = document.getElementById('menu-toggle');
  const closeBtn  = document.getElementById('sidebar-close');

  function openSidebar() {
    sidebar?.classList.add('open');
    backdrop?.classList.add('show');
    document.body.style.overflow = 'hidden';
  }
  function closeSidebar() {
    sidebar?.classList.remove('open');
    backdrop?.classList.remove('show');
    document.body.style.overflow = '';
  }
  menuBtn?.addEventListener('click', openSidebar);
  closeBtn?.addEventListener('click', closeSidebar);
  backdrop?.addEventListener('click', closeSidebar);

  // Close sidebar on resize
  window.addEventListener('resize', () => {
    if (window.innerWidth > 768) closeSidebar();
  });

  // ===== MODALS =====
  document.querySelectorAll('[data-modal]').forEach(btn => {
    btn.addEventListener('click', () => {
      const id = btn.getAttribute('data-modal');
      openModal(id);
    });
  });

  document.querySelectorAll('[data-modal-close]').forEach(btn => {
    btn.addEventListener('click', () => {
      const overlay = btn.closest('.modal-overlay');
      closeModal(overlay?.id);
    });
  });

  document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', (e) => {
      if (e.target === overlay) closeModal(overlay.id);
    });
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      document.querySelectorAll('.modal-overlay.open').forEach(o => closeModal(o.id));
    }
  });

  // ===== DELETE CONFIRM =====
  document.querySelectorAll('[data-delete]').forEach(btn => {
    btn.addEventListener('click', () => {
      const id   = btn.getAttribute('data-delete');
      const name = btn.getAttribute('data-name') || 'this record';
      const form = document.getElementById('delete-form');
      const msg  = document.getElementById('delete-msg');
      if (form && msg) {
        const url = 'records.php?action=delete&id=' + id;
        form.setAttribute('action', url);
        msg.textContent = 'Are you sure you want to delete "' + name + '"? This cannot be undone.';
        openModal('delete-modal');
      }
    });
  });

  // ===== EDIT RECORD =====
  document.querySelectorAll('[data-edit]').forEach(btn => {
    btn.addEventListener('click', () => {
      const data = JSON.parse(btn.getAttribute('data-edit'));
      fillEditModal(data);
      openModal('edit-modal');
    });
  });

  function fillEditModal(data) {
    const map = { title:'edit_title', category:'edit_category', description:'edit_description',
                  status:'edit_status', priority:'edit_priority', assigned_to:'edit_assigned',
                  due_date:'edit_due_date', id:'edit_id' };
    Object.entries(map).forEach(([k, v]) => {
      const el = document.getElementById(v);
      if (el && data[k] !== undefined) el.value = data[k] ?? '';
    });
  }

  // ===== AUTO-DISMISS ALERTS =====
  document.querySelectorAll('.alert[data-auto-dismiss]').forEach(alert => {
    setTimeout(() => {
      alert.style.transition = 'opacity 0.5s ease';
      alert.style.opacity = '0';
      setTimeout(() => alert.remove(), 500);
    }, 3500);
  });

  // ===== SEARCH =====
  const searchInput = document.getElementById('table-search');
  if (searchInput) {
    searchInput.addEventListener('input', function () {
      const q = this.value.toLowerCase();
      document.querySelectorAll('tbody tr').forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(q) ? '' : 'none';
      });
    });
  }

  // ===== TOPBAR SEARCH =====
  const topSearch = document.getElementById('topbar-search');
  if (topSearch) {
    topSearch.addEventListener('keydown', function (e) {
      if (e.key === 'Enter' && this.value.trim()) {
        window.location.href = 'records.php?search=' + encodeURIComponent(this.value.trim());
      }
    });
  }

  // ===== PASSWORD TOGGLE =====
  document.querySelectorAll('[data-pw-toggle]').forEach(btn => {
    btn.addEventListener('click', () => {
      const targetId = btn.getAttribute('data-pw-toggle');
      const input = document.getElementById(targetId);
      if (input) {
        input.type = input.type === 'password' ? 'text' : 'password';
        btn.textContent = input.type === 'password' ? '👁️' : '🙈';
      }
    });
  });

  // ===== FORM VALIDATION =====
  document.querySelectorAll('form[data-validate]').forEach(form => {
    form.addEventListener('submit', function (e) {
      let valid = true;
      this.querySelectorAll('[required]').forEach(field => {
        if (!field.value.trim()) {
          valid = false;
          field.style.borderColor = 'var(--danger)';
          field.style.boxShadow   = '0 0 0 3px rgba(239,68,68,0.15)';
        } else {
          field.style.borderColor = '';
          field.style.boxShadow   = '';
        }
      });
      if (!valid) { e.preventDefault(); showToast('Please fill in all required fields.', 'danger'); }
    });
  });

  // ===== TOAST =====
  window.showToast = function(msg, type='info') {
    let container = document.getElementById('toast-container');
    if (!container) {
      container = document.createElement('div');
      container.id = 'toast-container';
      container.style.cssText = 'position:fixed;bottom:20px;right:20px;z-index:9999;display:flex;flex-direction:column;gap:8px;';
      document.body.appendChild(container);
    }
    const colors = {info:'#3b82f6',success:'#10b981',danger:'#ef4444',warning:'#f59e0b'};
    const toast = document.createElement('div');
    toast.style.cssText = `background:var(--bg-panel);border:1px solid ${colors[type] || colors.info};border-radius:10px;padding:12px 18px;color:var(--text-primary);font-size:.875rem;box-shadow:0 4px 24px rgba(0,0,0,.5);max-width:320px;animation:slideUp .25s ease;`;
    toast.textContent = msg;
    container.appendChild(toast);
    setTimeout(() => { toast.style.opacity='0'; toast.style.transition='opacity .4s'; setTimeout(()=>toast.remove(), 400); }, 3000);
  };

  // ===== COUNTER ANIMATION =====
  document.querySelectorAll('.stat-value[data-count]').forEach(el => {
    const target = parseInt(el.getAttribute('data-count'));
    let current = 0;
    const step = Math.ceil(target / 40);
    const timer = setInterval(() => {
      current = Math.min(current + step, target);
      el.textContent = current.toLocaleString();
      if (current >= target) clearInterval(timer);
    }, 25);
  });

  // ===== SHOW ACTIVE NAV =====
  const path = window.location.pathname.split('/').pop();
  document.querySelectorAll('.nav-item').forEach(link => {
    const href = link.getAttribute('href');
    if (href && href.includes(path)) link.classList.add('active');
  });
});

function openModal(id) {
  const overlay = document.getElementById(id);
  if (overlay) overlay.classList.add('open');
}
function closeModal(id) {
  const overlay = document.getElementById(id);
  if (overlay) overlay.classList.remove('open');
}
