<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css'])
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Manrope', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background:
                radial-gradient(circle at 10% 5%, rgba(34, 197, 94, 0.14) 0%, rgba(34, 197, 94, 0) 35%),
                radial-gradient(circle at 90% 90%, rgba(14, 116, 144, 0.14) 0%, rgba(14, 116, 144, 0) 40%),
                linear-gradient(135deg, #f7fffd 0%, #eefdf6 55%, #f2fcff 100%);
            background-attachment: fixed;
            color: #111827;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            overflow-x: hidden;
        }

        .container {
            display: flex;
            min-height: 100vh;
            width: 100%;
            max-width: 100vw;
            overflow-x: hidden;
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            z-index: 99;
            border-right: 1px solid rgba(226, 232, 240, 0.8);
            transition: transform 0.28s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body.sidebar-collapsed .sidebar {
            transform: translateX(-100%);
        }

        .sidebar-logo {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid rgba(226, 232, 240, 0.6);
            background: linear-gradient(135deg, #16a34a 0%, #0e7490 100%);
        }

        .sidebar-logo-row {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .sidebar-logo-toggle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            border: 1px solid rgba(255, 255, 255, 0.35);
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.16);
            color: #ffffff;
            font-size: 1.1rem;
            line-height: 1;
            cursor: pointer;
            flex-shrink: 0;
            transition: all 0.2s ease;
        }

        .sidebar-logo-toggle:hover {
            background: rgba(255, 255, 255, 0.26);
            border-color: rgba(255, 255, 255, 0.5);
        }

        .sidebar-logo-icon {
            width: 48px;
            height: 48px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .sidebar-logo-text {
            font-size: 1.5rem;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: -0.02em;
        }

        .sidebar-subtitle {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.8);
            font-weight: 500;
            margin-top: 0.25rem;
        }

        .sidebar-nav {
            padding: 1.5rem 1rem;
        }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 0.875rem;
            padding: 0.875rem 1rem;
            color: #374151;
            text-decoration: none;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 12px;
            font-weight: 500;
            font-size: 0.95rem;
            position: relative;
            overflow: hidden;
        }

        .sidebar-nav a::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 0;
            background: linear-gradient(90deg, #0e7490, transparent);
            transition: width 0.3s ease;
        }

        .sidebar-nav a:hover {
            background: #ecfeff;
            color: #155e75;
            transform: translateX(4px);
        }

        .sidebar-nav a:hover::before {
            width: 4px;
        }

        .sidebar-nav a.active {
            background: linear-gradient(135deg, #16a34a, #0e7490);
            color: #ffffff;
            font-weight: 600;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
        }

        .sidebar-nav-icon {
            font-size: 1.25rem;
            width: 24px;
            text-align: center;
        }

        .sidebar-footer {
            padding: 1.5rem 1rem;
            border-top: 1px solid rgba(226, 232, 240, 0.6);
            position: absolute;
            bottom: 0;
            width: 100%;
            background: rgba(255, 255, 255, 0.5);
        }

        .logout-btn {
            width: 100%;
            padding: 0.875rem;
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #dc2626;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        .logout-btn:hover {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: #ffffff;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
            transform: translateY(-1px);
        }

        /* Main Content */
        .main-wrapper {
            margin-left: 280px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
            transition: margin-left 0.28s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body.sidebar-collapsed .main-wrapper {
            margin-left: 0;
        }

        /* Header */
        header {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            padding: 2rem 2.5rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 50;
            border-bottom: 1px solid rgba(226, 232, 240, 0.6);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
        }

        .header-main {
            flex: 1;
            min-width: 0;
        }

        .hamburger-btn {
            display: none;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            padding: 0;
            border: 1px solid #d1d5db;
            border-radius: 12px;
            background: #ffffff;
            color: #374151;
            cursor: pointer;
            flex-shrink: 0;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            transition: all 0.2s ease;
        }

        body.sidebar-collapsed .hamburger-btn {
            display: inline-flex;
        }

        .hamburger-icon {
            font-size: 1.35rem;
            line-height: 1;
        }

        .hamburger-btn:hover {
            background: #ecfeff;
            color: #155e75;
            border-color: #67e8f9;
        }

        .mobile-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(17, 24, 39, 0.45);
            backdrop-filter: blur(3px);
            -webkit-backdrop-filter: blur(3px);
            z-index: 90;
        }

        .header-title {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, #111827, #1e3a8a);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -0.02em;
        }

        .header-subtitle {
            font-size: 0.9375rem;
            color: #6b7280;
            font-weight: 400;
        }

        .header-actions {
            display: flex;
            gap: 0.75rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            cursor: pointer;
            font-size: 0.9375rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        .btn-primary {
            background: linear-gradient(135deg, #16a34a, #0e7490);
            color: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
        }

        .btn-primary:active {
            transform: translateY(0);
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        /* Content Area */
        .content {
            flex: 1;
            padding: 2.5rem;
            overflow-y: auto;
            width: 100%;
            min-width: 0;
        }

        /* Form Container */
        .py-12 {
            padding: 0;
        }

        .max-w-7xl {
            max-width: 900px;
        }

        .mx-auto {
            margin-left: auto;
            margin-right: auto;
        }

        .bg-white {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .shadow-sm {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
        }

        .sm\:rounded-lg {
            border-radius: 20px;
        }

        .p-6 {
            padding: 2.5rem;
        }

        .overflow-hidden {
            overflow: hidden;
        }

        /* Form Styles */
        .mb-4 {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        input[type="text"],
        input[type="number"],
        input[type="email"],
        input[type="password"],
        input[type="date"],
        textarea,
        select {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 1rem;
            font-family: 'Manrope', sans-serif;
            color: #111827;
            background: #ffffff;
            transition: all 0.2s ease;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        input:focus,
        textarea:focus,
        select:focus {
            outline: none;
            border-color: #0e7490;
            box-shadow: 0 0 0 3px rgba(14, 116, 144, 0.12), 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        input:hover,
        textarea:hover,
        select:hover {
            border-color: #d1d5db;
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }

        select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3E%3C/svg%3E");
            background-position: right 0.75rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
        }

        /* Error Messages */
        .text-red-500 {
            color: #ef4444;
        }

        .text-xs {
            font-size: 0.75rem;
        }

        .mt-1 {
            margin-top: 0.375rem;
        }

        /* Form Actions */
        .flex {
            display: flex;
        }

        .items-center {
            align-items: center;
        }

        .justify-between {
            justify-content: space-between;
        }

        button[type="submit"] {
            background: linear-gradient(135deg, #16a34a, #0e7490);
            color: white;
            font-weight: 600;
            padding: 0.875rem 2rem;
            border-radius: 12px;
            border: none;
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
            font-family: 'Manrope', sans-serif;
        }

        button[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
        }

        button[type="submit"]:active {
            transform: translateY(0);
        }

        a[href*="index"] {
            color: #6b7280;
            font-weight: 600;
            text-decoration: none;
            padding: 0.875rem 1.5rem;
            border-radius: 12px;
            transition: all 0.2s ease;
            border: 2px solid transparent;
        }

        a[href*="index"]:hover {
            color: #111827;
            background: #f3f4f6;
            border-color: #e5e7eb;
        }

        /* Footer */
        footer {
            padding: 2rem;
            text-align: center;
            color: #6b7280;
            font-size: 0.875rem;
            border-top: 1px solid rgba(226, 232, 240, 0.6);
            background: rgba(255, 255, 255, 0.5);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 280px;
                max-width: calc(100% - 52px);
                height: 100vh;
                position: fixed;
                top: 0;
                left: 0;
                z-index: 99;
                transform: translateX(-100%);
            }

            body.sidebar-open .sidebar {
                transform: translateX(0);
            }

            .sidebar-logo {
                padding: 1.5rem;
            }

            .sidebar-logo-toggle {
                display: none;
            }

            .sidebar-nav {
                display: flex;
                flex-direction: column;
                overflow-x: visible;
                padding: 1rem;
                gap: 0.5rem;
            }

            .sidebar-nav a {
                white-space: nowrap;
                flex-shrink: 0;
            }

            .sidebar-footer {
                position: static;
                width: 100%;
                padding: 1.5rem;
            }

            .main-wrapper {
                margin-left: 0;
            }

            .content {
                padding: 1.5rem;
            }

            header {
                padding: 1.5rem;
            }

            .header-content {
                align-items: center;
            }

            .hamburger-btn {
                display: inline-flex;
                width: 42px;
                height: 42px;
            }

            body.sidebar-open .mobile-overlay {
                display: block;
            }

            .header-title {
                font-size: 1.5rem;
            }

            .header-actions {
                flex-direction: column;
                width: 100%;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            .p-6 {
                padding: 1.5rem;
            }

            .flex {
                flex-direction: column;
                gap: 0.75rem;
            }

            button[type="submit"],
            a[href*="index"] {
                width: 100%;
                text-align: center;
                justify-content: center;
                display: inline-flex;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <aside class="sidebar" id="appSidebar">
            <div class="sidebar-logo">
                <div class="sidebar-logo-row">
                    <button class="sidebar-logo-toggle" type="button" aria-label="Toggle navigation menu" data-sidebar-toggle>☰</button>
                    <div class="sidebar-logo-icon">💊</div>
                    <div class="sidebar-logo-text">PharmaSys</div>
                </div>
            </div>

            <nav class="sidebar-nav">
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <span class="sidebar-nav-icon">📊</span>
                    Dashboard
                </a>
                <a href="{{ route('records.medicines') }}" class="{{ request()->routeIs('medicines.*') ? 'active' : '' }}">
                    <span class="sidebar-nav-icon">💊</span>
                    Medicines
                </a>
                <a href="{{ route('records.inventory') }}" class="{{ request()->routeIs('inventory.*') || request()->is('inventory') ? 'active' : '' }}">
                    <span class="sidebar-nav-icon">📦</span>
                    Inventory
                </a>
                <a href="{{ route('records.sales') }}" class="{{ request()->routeIs('sales.*') ? 'active' : '' }}">
                    <span class="sidebar-nav-icon">💰</span>
                    Sales
                </a>
                <a href="{{ route('records.suppliers') }}" class="{{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                    <span class="sidebar-nav-icon">🤝</span>
                    Suppliers
                </a>
            </nav>

            <div class="sidebar-footer">
                <form method="POST" action="{{ route('logout') }}" style="width: 100%;">
                    @csrf
                    <button type="submit" class="logout-btn">🚪 Logout</button>
                </form>
            </div>
        </aside>

        <div class="mobile-overlay" id="sidebarOverlay" aria-hidden="true"></div>

        <!-- Main Content -->
        <div class="main-wrapper">
            @hasSection('header')
                <header>
                    <div class="header-content">
                        <button type="button" class="hamburger-btn" id="navToggle" aria-label="Toggle navigation menu" aria-controls="appSidebar" aria-expanded="false" data-sidebar-toggle>
                            <span class="hamburger-icon" id="navToggleIcon">&#9776;</span>
                        </button>
                        <div class="header-main">
                            @yield('header')
                        </div>
                    </div>
                </header>
            @endif

            <div class="content">
                <!-- All content removed as requested -->
            </div>

            <footer>
                <p>&copy; 2026 PharmaSys - Pharmacy Operations and Medication Records.</p>
            </footer>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('appSidebar');
            const toggle = document.getElementById('navToggle');
            const toggleButtons = document.querySelectorAll('[data-sidebar-toggle]');
            const toggleIcon = document.getElementById('navToggleIcon');
            const overlay = document.getElementById('sidebarOverlay');
            const body = document.body;
            const storageKey = 'pharmasys-sidebar-collapsed';
            const mobileBreakpoint = 768;

            if (!sidebar || !toggle || !toggleIcon || toggleButtons.length === 0 || !overlay || !body) {
                return;
            }

            const isMobile = function () {
                return window.innerWidth <= mobileBreakpoint;
            };

            const updateToggle = function () {
                if (isMobile()) {
                    const isOpen = body.classList.contains('sidebar-open');
                    toggleIcon.innerHTML = isOpen ? '&times;' : '&#9776;';
                    toggleButtons.forEach(function (button) {
                        button.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                        button.setAttribute('aria-label', isOpen ? 'Hide navigation menu' : 'Show navigation menu');
                        button.setAttribute('title', isOpen ? 'Hide menu' : 'Show menu');
                    });
                    return;
                }

                const isCollapsed = body.classList.contains('sidebar-collapsed');
                toggleIcon.innerHTML = isCollapsed ? '&#9776;' : '&times;';
                toggleButtons.forEach(function (button) {
                    button.setAttribute('aria-expanded', isCollapsed ? 'false' : 'true');
                    button.setAttribute('aria-label', isCollapsed ? 'Show navigation menu' : 'Hide navigation menu');
                    button.setAttribute('title', isCollapsed ? 'Show menu' : 'Hide menu');
                });
            };

            const closeMobileSidebar = function () {
                body.classList.remove('sidebar-open');
                updateToggle();
            };

            const applyStoredDesktopPreference = function () {
                if (isMobile()) {
                    body.classList.remove('sidebar-collapsed');
                    updateToggle();
                    return;
                }

                if (localStorage.getItem(storageKey) === 'true') {
                    body.classList.add('sidebar-collapsed');
                } else {
                    body.classList.remove('sidebar-collapsed');
                }

                updateToggle();
            };

            applyStoredDesktopPreference();

            toggleButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    if (isMobile()) {
                        if (body.classList.contains('sidebar-open')) {
                            closeMobileSidebar();
                        } else {
                            body.classList.add('sidebar-open');
                            updateToggle();
                        }

                        return;
                    }

                    const isCollapsed = body.classList.toggle('sidebar-collapsed');
                    localStorage.setItem(storageKey, isCollapsed ? 'true' : 'false');
                    updateToggle();
                });
            });

            overlay.addEventListener('click', closeMobileSidebar);

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    closeMobileSidebar();
                }
            });

            sidebar.querySelectorAll('a').forEach(function (link) {
                link.addEventListener('click', function () {
                    if (isMobile()) {
                        closeMobileSidebar();
                    }
                });
            });

            window.addEventListener('resize', function () {
                if (isMobile()) {
                    body.classList.remove('sidebar-collapsed');
                } else {
                    body.classList.remove('sidebar-open');
                    if (localStorage.getItem(storageKey) === 'true') {
                        body.classList.add('sidebar-collapsed');
                    }
                }

                updateToggle();
            });

            document.addEventListener('submit', function (event) {
                const form = event.target;

                if (!(form instanceof HTMLFormElement)) {
                    return;
                }

                const methodField = form.querySelector('input[name="_method"]');
                const isDelete = methodField && String(methodField.value).toUpperCase() === 'DELETE';

                if (!isDelete) {
                    return;
                }

                let message = 'Are you sure you want to delete this record?';
                const submitter = event.submitter;

                if (submitter && submitter.dataset && submitter.dataset.confirmDelete) {
                    message = submitter.dataset.confirmDelete;
                } else {
                    const deleteButton = form.querySelector('[data-confirm-delete]');
                    if (deleteButton && deleteButton.dataset.confirmDelete) {
                        message = deleteButton.dataset.confirmDelete;
                    }
                }

                if (!window.confirm(message)) {
                    event.preventDefault();
                }
            });

            updateToggle();
        });
    </script>
</body>
</html>
