<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'PharmaCare') — Pharmacy Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous">
    <style>
        body { font-family: system-ui, -apple-system, sans-serif; }
        .sidebar-active { background: rgba(255,255,255,0.13); color: #fff !important; }
        .sidebar-active i { color: #86efac !important; }
        .spin { animation: spin 0.8s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 10px; }
        .tbl-row { transition: background 0.1s; }
        .tbl-row:hover { background: #f9fafb; }
        .card-lift { transition: transform .15s, box-shadow .15s; }
        .card-lift:hover { transform: translateY(-3px); box-shadow: 0 12px 28px -6px rgba(0,0,0,.1); }
        .modal-bg { backdrop-filter: blur(3px); }
        input, select, textarea { transition: border-color .15s, box-shadow .15s; }
        .ring-inp:focus { outline: none; border-color: #16a34a; box-shadow: 0 0 0 3px rgba(22,163,74,.15); }
    </style>
</head>
<body class="bg-gray-50">
<div class="flex min-h-screen">

    {{-- ── Sidebar ─────────────────────────────────────────── --}}
    <aside class="w-64 bg-gradient-to-b from-green-900 to-green-950 text-white flex flex-col fixed inset-y-0 left-0 z-30 shadow-2xl">

        {{-- Brand --}}
        <div class="flex items-center gap-3 px-5 py-5 border-b border-white/10">
            <div class="h-10 w-10 rounded-xl bg-green-400 flex items-center justify-center shadow-inner flex-shrink-0">
                <i class="fas fa-pills text-green-900 text-lg"></i>
            </div>
            <div>
                <p class="font-extrabold text-base leading-tight">PharmaCare</p>
                <p class="text-[11px] text-green-400 font-medium">Management System</p>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-3 py-5 space-y-0.5 overflow-y-auto">
            <p class="px-3 mb-2 text-[10px] font-bold text-green-600 uppercase tracking-widest">Main Menu</p>

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
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-green-300 hover:bg-white/10 hover:text-white transition-all duration-150
                      {{ request()->routeIs($nav['route']) ? 'sidebar-active' : '' }}">
                <i class="fas {{ $nav['icon'] }} w-4 text-center text-xs text-green-400"></i>
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
                <div class="h-8 w-8 rounded-full bg-green-500/70 flex items-center justify-center text-xs font-bold flex-shrink-0">A</div>
                <div class="min-w-0">
                    <p class="text-sm font-semibold truncate leading-tight">Administrator</p>
                    <p class="text-[11px] text-green-400 truncate">System Admin</p>
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
                    <span class="text-green-600 font-semibold">@yield('page-title', 'Dashboard')</span>
                </nav>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-sm text-gray-500 flex items-center gap-1.5">
                    <span class="h-2 w-2 rounded-full bg-green-500 inline-block animate-pulse"></span>
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
// ── Shared helpers ────────────────────────────────────────────
function showToast(msg, type = 'success') {
    const t = document.getElementById('toast');
    const b = document.getElementById('toast-box');
    const i = document.getElementById('toast-icon');
    document.getElementById('toast-msg').textContent = msg;
    const cfg = {
        success: ['bg-green-600',  'fa-check-circle'],
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
    return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(n ?? 0);
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
