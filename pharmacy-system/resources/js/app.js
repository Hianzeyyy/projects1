import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
	const body = document.body;

	// Dashboard sidebar toggle behavior
	if (body && body.classList.contains('dashboard-page')) {
		const toggleButtons = document.querySelectorAll('[data-sidebar-toggle]');
		const sidebar = document.querySelector('[data-sidebar]');
		const overlay = document.querySelector('[data-sidebar-overlay]');
		const mobileBreakpoint = 1100;

		const isMobile = () => window.innerWidth <= mobileBreakpoint;

		const syncToggleState = () => {
			const expanded = isMobile() ? body.classList.contains('sidebar-open') : !body.classList.contains('sidebar-collapsed');

			toggleButtons.forEach((button) => {
				button.setAttribute('aria-expanded', expanded ? 'true' : 'false');
			});
		};

		const toggleSidebar = () => {
			if (isMobile()) {
				body.classList.toggle('sidebar-open');
				body.classList.remove('sidebar-collapsed');
			} else {
				body.classList.toggle('sidebar-collapsed');
				body.classList.remove('sidebar-open');
			}

			syncToggleState();
		};

		const closeMobileSidebar = () => {
			if (!isMobile()) {
				return;
			}

			body.classList.remove('sidebar-open');
			syncToggleState();
		};

		if (toggleButtons.length > 0 && sidebar && overlay) {
			toggleButtons.forEach((button) => {
				button.addEventListener('click', toggleSidebar);
			});

			overlay.addEventListener('click', closeMobileSidebar);

			window.addEventListener('resize', () => {
				if (isMobile()) {
					body.classList.remove('sidebar-open');
				} else {
					body.classList.remove('sidebar-open');
				}

				syncToggleState();
			});

			document.addEventListener('keydown', (event) => {
				if (event.key === 'Escape' && isMobile()) {
					closeMobileSidebar();
				}
			});

			syncToggleState();
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

	// Login and Register form handler
	const loginForm = document.querySelector('form[action*="login"]');
	const registerForm = document.querySelector('form[action*="register"]');

	const handleFormSubmit = (form) => {
		if (!form) return;

		form.addEventListener('submit', (event) => {
			const submitButton = form.querySelector('button[type="submit"]');
			if (submitButton) {
				submitButton.disabled = true;
				submitButton.style.opacity = '0.7';
				submitButton.textContent = submitButton.textContent.includes('Sign in') ? 'Signing in...' : 'Signing up...';
			}
		});
	};

	handleFormSubmit(loginForm);
	handleFormSubmit(registerForm);
});
