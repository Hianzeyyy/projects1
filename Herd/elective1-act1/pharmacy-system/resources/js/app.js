import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
	const body = document.body;

	// Dashboard sidebar toggle behavior
	if (body && body.classList.contains('dashboard-page')) {
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
