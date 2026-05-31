<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'PharmaCare') — Pharmacy Management System</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link rel="stylesheet" href="/css/app.css">
    @endif
</head>
<body class="app-shell bg-violet-light">

<!-- Sidebar Toggle Button for Mobile -->
<button id="sidebar-toggle" class="fixed top-4 left-4 z-40 bg-violet-main text-white rounded-full p-2 shadow-lg md:hidden" style="display:none">
    <i class="fas fa-bars"></i>
</button>

<div class="flex min-h-screen">

    {{-- ── Sidebar ─────────────────────────────────────────── --}}
    <aside class="w-64 bg-gradient-to-b from-violet-dark to-violet-main text-white flex flex-col fixed inset-y-0 left-0 z-30 shadow-2xl">

        {{-- Brand --}}
        <div class="flex items-center gap-3 px-5 py-5 border-b border-white/10">
            <div class="h-10 w-10 rounded-xl bg-violet-main flex items-center justify-center shadow-inner flex-shrink-0">
                <i class="fas fa-pills text-violet-dark text-lg"></i>
            </div>
            <div>
                <p class="font-extrabold text-base leading-tight">PharmaCare</p>
                <p class="text-[11px] text-violet-light font-medium">Management System</p>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-3 py-5 space-y-0.5 overflow-y-auto">
            <p class="px-3 mb-2 text-[10px] font-bold text-violet-main uppercase tracking-widest">Main Menu</p>

            @php
                $navLinks = [
                    ['route' => 'dashboard',  'icon' => 'fa-gauge-high',  'label' => 'Dashboard'],
                    ['route' => 'medicines',  'icon' => 'fa-capsules',    'label' => 'Medicines'],
                    ['route' => 'inventory',  'icon' => 'fa-layer-group', 'label' => 'Inventory'],
                    ['route' => 'sales',      'icon' => 'fa-receipt',     'label' => 'Sales'],
                    ['route' => 'suppliers',  'icon' => 'fa-truck-fast',  'label' => 'Suppliers'],
                ];
            @endphp

            @foreach($navLinks as $nav)
            <a href="{{ route($nav['route']) }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-[#b651f0] hover:bg-white/10 hover:text-white transition-all duration-150
                        {{ request()->routeIs($nav['route']) ? 'sidebar-active' : '' }}">
                    <i class="fas {{ $nav['icon'] }} w-4 text-center text-xs text-violet-light"></i>
                {{ $nav['label'] }}
                @if($nav['route'] === 'inventory')
                <span id="nav-lowstock-badge" class="ml-auto hidden px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-red-500 text-white"></span>
                @endif
            </a>
            @endforeach
        </nav>

        {{-- Profile --}}
        <div class="px-4 py-4 border-t border-white/10">
            <div class="flex items-center gap-2.5">
                <div class="h-8 w-8 rounded-full bg-violet-main/70 flex items-center justify-center text-xs font-bold flex-shrink-0">A</div>
                <div class="min-w-0">
                    <p class="text-sm font-semibold truncate leading-tight">Administrator</p>
                    <p class="text-[11px] text-violet-light truncate">System Admin</p>
                </div>
            </div>
        </div>
    </aside>

    {{-- ── Main Content ─────────────────────────────────────── --}}
    <div class="ml-64 flex-1 flex flex-col min-w-0">

        {{-- Top Header --}}
        <header class="bg-white border-b border-gray-200 px-8 py-4 flex items-center justify-between sticky top-0 z-20 shadow-sm">
            <div>
                <h1 class="text-xl font-bold text-gray-800">@yield('page-title', 'Dashboard')</h1>
                <nav class="flex items-center gap-1 text-xs text-gray-400 mt-0.5">
                    <span>PharmaCare</span>
                    <i class="fas fa-chevron-right text-[8px]"></i>
                    <span class="text-violet-main font-semibold">@yield('page-title', 'Dashboard')</span>
                </nav>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-sm text-gray-500 flex items-center gap-1.5">
                    <span class="h-2 w-2 rounded-full bg-violet-main inline-block animate-pulse"></span>
                    {{ now()->format('M d, Y') }}
                </span>
            </div>
        </header>

        {{-- Page Content --}}
        <main class="flex-1 p-8">
            @yield('content')
        </main>
    </div>
</div>

{{-- ── Toast ───────────────────────────────────────────── --}}
<div id="toast" class="fixed bottom-5 right-5 z-50 opacity-0 translate-y-2 pointer-events-none transition-all duration-300">
    <div id="toast-box" class="flex items-center gap-3 px-5 py-3.5 rounded-2xl shadow-xl text-white text-sm font-medium min-w-[220px]">
        <i id="toast-icon" class="fas fa-check-circle text-base"></i>
        <span id="toast-msg"></span>
    </div>
</div>

<script>
// Sidebar toggle for mobile
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.querySelector('aside');
    const toggleBtn = document.getElementById('sidebar-toggle');
    function checkScreen() {
        if (window.innerWidth <= 900) {
            toggleBtn.style.display = 'block';
            sidebar.classList.remove('active');
        } else {
            toggleBtn.style.display = 'none';
            sidebar.classList.remove('active');
        }
    }
    toggleBtn.addEventListener('click', function() {
        sidebar.classList.toggle('active');
    });
    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 900 && sidebar.classList.contains('active')) {
            if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
                sidebar.classList.remove('active');
            }
        }
    });
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            sidebar.classList.remove('active');
        }
    });
    window.addEventListener('resize', checkScreen);
    checkScreen();
});
// ── Shared helpers ────────────────────────────────────────────
function showToast(msg, type = 'success') {
    const t = document.getElementById('toast');
    const b = document.getElementById('toast-box');
    const i = document.getElementById('toast-icon');
    document.getElementById('toast-msg').textContent = msg;
    const cfg = {
        success: ['bg-[#b651f0]',  'fa-check-circle'],
        error:   ['bg-red-500',    'fa-circle-xmark'],
        warning: ['bg-amber-500',  'fa-triangle-exclamation'],
        info:    ['bg-blue-500',   'fa-circle-info'],
    };
    const [bg, cls] = cfg[type] ?? cfg.success;
    b.className = `flex items-center gap-3 px-5 py-3.5 rounded-2xl shadow-xl text-white text-sm font-medium min-w-[220px] ${bg}`;
    i.className = `fas ${cls} text-base`;
    t.classList.remove('opacity-0','translate-y-2','pointer-events-none');
    clearTimeout(t._tmr);
    t._tmr = setTimeout(() => t.classList.add('opacity-0','translate-y-2','pointer-events-none'), 3200);
}

async function api(url, method = 'GET', body = null) {
    const opts = {
        method,
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' }
    };
    if (body) opts.body = JSON.stringify(body);
    const r = await fetch(url, opts);
    const json = await r.json();
    if (!r.ok) throw new Error(json.message ?? json.error ?? 'Request failed');
    return json;
}

function fmtDate(d) {
    if (!d) return '—';
    return new Date(d).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: '2-digit' });
}
function fmtMoney(n) {
    return new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(n ?? 0);
}
function toInputDate(d) { return d ? d.split('T')[0] : ''; }
function escHtml(s) {
    return String(s ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}

// Low-stock badge in sidebar
(async function updateBadge() {
    try {
        const inv = await api('/api/inventory');
        const count = inv.filter(i => i.quantity <= i.reorder_level).length;
        const badge = document.getElementById('nav-lowstock-badge');
        if (badge && count > 0) { badge.textContent = count; badge.classList.remove('hidden'); }
    } catch(_) {}
})();
</script>

@stack('scripts')
</body>
</html>
