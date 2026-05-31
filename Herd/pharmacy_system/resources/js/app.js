import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    const body = document.body;

    // Multi-button portal navbar dropdown behavior (menu + account + submenu)
    const portalDropdownGroups = document.querySelectorAll('[data-portal-dropdown-group]');

    // === Records Page Search Bar Functionality ===
    const searchInput = document.querySelector('.records-search-input');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const query = this.value.trim().toLowerCase();

            // Table search (medicines, inventory, sales)
            const table = document.querySelector('.data-table table');
            if (table) {
                const rows = table.querySelectorAll('tbody tr');
                let anyVisible = false;
                rows.forEach(row => {
                    // Only hide data rows, not empty state rows
                    if (row.querySelector('.empty-state')) return;
                    const rowText = Array.from(row.cells).map(cell => cell.textContent.toLowerCase()).join(' ');
                    const match = rowText.includes(query);
                    row.style.display = match ? '' : 'none';
                    if (match) anyVisible = true;
                });
                // Show/hide empty state row if nothing matches
                const emptyStateCell = table.querySelector('tbody tr .empty-state');
                const emptyRow = emptyStateCell ? emptyStateCell.closest('tr') : null;
                if (emptyRow) emptyRow.style.display = anyVisible ? 'none' : '';
            }

            // Card grid search (suppliers)
            const cardGrid = document.querySelector('.records-card-grid');
            if (cardGrid) {
                const cards = cardGrid.querySelectorAll('.record-card');
                let anyVisible = false;
                cards.forEach(card => {
                    const cardText = card.textContent.toLowerCase();
                    const match = cardText.includes(query);
                    card.style.display = match ? '' : 'none';
                    if (match) anyVisible = true;
                });
                // Show/hide empty state if nothing matches
                const emptyState = cardGrid.querySelector('.empty-state');
                if (emptyState) emptyState.style.display = anyVisible ? 'none' : '';
            }
        });
    }

    const closeDropdownGroup = (group) => {
        const toggle = group.querySelector('[data-portal-dropdown-toggle]');
        const panel = group.querySelector('[data-portal-dropdown-panel]');
        if (!toggle || !panel) {
            return;
        }

        panel.hidden = true;
        toggle.setAttribute('aria-expanded', 'false');

        const submenuToggle = group.querySelector('[data-portal-submenu-toggle]');
        const submenuPanel = group.querySelector('[data-portal-submenu-panel]');
        if (submenuToggle && submenuPanel) {
            submenuPanel.hidden = true;
            submenuToggle.setAttribute('aria-expanded', 'false');
        }
    };

    const closeAllDropdownGroups = () => {
        portalDropdownGroups.forEach((group) => closeDropdownGroup(group));
    };

    if (portalDropdownGroups.length > 0) {
        portalDropdownGroups.forEach((group) => {
            const toggle = group.querySelector('[data-portal-dropdown-toggle]');
            const panel = group.querySelector('[data-portal-dropdown-panel]');
            const submenuToggle = group.querySelector('[data-portal-submenu-toggle]');
            const submenuPanel = group.querySelector('[data-portal-submenu-panel]');

            if (!toggle || !panel) {
                return;
            }

            panel.hidden = true;

            toggle.addEventListener('click', (event) => {
                event.stopPropagation();
                const shouldOpen = panel.hidden;
                closeAllDropdownGroups();
                if (shouldOpen) {
                    panel.hidden = false;
                    toggle.setAttribute('aria-expanded', 'true');
                }
            });

            panel.addEventListener('click', (event) => {
                event.stopPropagation();
            });

            if (submenuToggle && submenuPanel) {
                submenuPanel.hidden = true;
                submenuToggle.addEventListener('click', (event) => {
                    event.stopPropagation();
                    const shouldOpenSubmenu = submenuPanel.hidden;
                    submenuPanel.hidden = !shouldOpenSubmenu;
                    submenuToggle.setAttribute('aria-expanded', shouldOpenSubmenu ? 'true' : 'false');
                });
            }
        });

        document.addEventListener('click', closeAllDropdownGroups);
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                closeAllDropdownGroups();
            }
        });
    }

    // Shared sidebar toggle behavior for dashboard and records/management pages
    if (body && (body.classList.contains('dashboard-page') || body.classList.contains('app-shell'))) {
        const toggleButton = document.querySelector('[data-sidebar-toggle]');
        const sidebar = document.querySelector('[data-sidebar]');
        const overlay = document.querySelector('[data-sidebar-overlay]');

        const closeSidebar = () => {
            body.classList.remove('sidebar-open');
            if (toggleButton) {
                toggleButton.setAttribute('aria-expanded', 'false');
            }
        };

        const openSidebar = () => {
            body.classList.add('sidebar-open');
            if (toggleButton) {
                toggleButton.setAttribute('aria-expanded', 'true');
            }
        };

        if (toggleButton && sidebar && overlay) {
            toggleButton.setAttribute('aria-expanded', 'false');

            toggleButton.addEventListener('click', () => {
                if (body.classList.contains('sidebar-open')) {
                    closeSidebar();
                } else {
                    openSidebar();
                }
            });

            overlay.addEventListener('click', closeSidebar);

            window.addEventListener('resize', () => {
                if (window.innerWidth > 900) {
                    closeSidebar();
                }
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    closeSidebar();
                }
            });
        }
    }

    // Centralized delete confirmation for records pages
    document.querySelectorAll('[data-confirm-delete]').forEach((button) => {
        button.addEventListener('click', (event) => {
            const message = button.getAttribute('data-confirm-delete') || 'Are you sure you want to delete this record?';

            if (!window.confirm(message)) {
                event.preventDefault();
            }
        });
    });
});